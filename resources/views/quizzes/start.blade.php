@extends('layouts.student')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800">{{ $quiz->name }}</h2>
        <span id="timer" class="text-gray-600 font-semibold">Waktu: {{ $quiz->duration }}:00</span>
    </div>
    <div class="neo-card p-6">
        @if ($questions->isEmpty())
            <p class="text-gray-600">Tidak ada soal untuk quiz ini.</p>
        @else
            <form method="POST" action="{{ route('quizzes.submit', $quiz) }}" id="quiz-form">
                @csrf
                <div class="mb-6">
                    <label for="current-question" class="text-gray-600">Soal: <span id="current-question-number">1</span> dari {{ $questions->count() }}</label>
                </div>
                <div id="question-container">
                    @foreach ($questions as $index => $question)
                        <div class="question-item hidden" data-question-id="{{ $question->id }}">
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">Soal {{ $index + 1 }}</h3>
                            <p class="text-gray-600 mb-4">{!! $question->content !!}</p>
                            @if ($question->image_path)
                                <div class="mb-4">
                                    <img src="{{ asset('storage/' . $question->image_path) }}" alt="Gambar Soal" class="max-w-full h-auto rounded-lg shadow-neo-light">
                                </div>
                            @endif
                            @foreach ($question->options as $option)
                                <div class="flex items-center mb-2">
                                    <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option->id }}" id="option_{{ $option->id }}"
                                        class="neo-input" {{ isset($userAnswers[$question->id]) && $userAnswers[$question->id] == $option->id ? 'checked' : '' }}>
                                    <label for="option_{{ $option->id }}" class="ml-2 text-gray-600">{!! $option->content !!}</label>
                                </div>
                            @endforeach
                            <div id="feedback-{{ $question->id }}" class="mt-2 hidden"></div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-6 flex justify-between">
                    <button type="button" id="prev-question" class="secondary-button" disabled>Soal Sebelumnya</button>
                    <button type="button" id="submit-button" class="primary-button">Selesai</button>
                    <button type="button" id="next-question" class="secondary-button">Soal Selanjutnya</button>
                </div>

                <!-- Map Soal -->
                <div class="mt-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Map Soal</h3>
                    <div class="flex flex-wrap gap-2" id="question-map">
                        @foreach ($questions as $index => $question)
                            <?php $isAnswered = isset($userAnswers[$question->id]); ?>
                            <button type="button"
                                class="secondary-button px-3 py-1 rounded-full text-sm {{ $isAnswered ? 'bg-blue-200 text-blue-800' : 'bg-gray-200 text-gray-800' }}"
                                data-question-index="{{ $index }}" data-question-id="{{ $question->id }}" onclick="goToQuestion({{ $index }})">
                                {{ $index + 1 }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Modal Konfirmasi -->
                <div id="confirm-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
                    <div class="neo-card p-6 max-w-sm w-full">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Konfirmasi Selesai Quiz</h3>
                        <p class="text-gray-600 mb-4">Apakah Anda yakin ingin mengakhiri quiz? Jawaban Anda akan disimpan dan quiz akan selesai.</p>
                        <div class="flex justify-end gap-2">
                            <button type="button" id="cancel-submit" class="secondary-button">Batal</button>
                            <button type="button" id="confirm-submit" class="primary-button">Ya, Selesai</button>
                        </div>
                    </div>
                </div>
            </form>
        @endif
    </div>

    <script>
        let currentIndex = 0;
        const questions = document.querySelectorAll('.question-item');
        const totalQuestions = questions.length;
        const quizId = {{ $quiz->id }};
        const endTime = new Date('{{ $endTime->toIso8601String() }}').getTime();
        let userAnswers = JSON.parse(localStorage.getItem(`quiz_answers_${quizId}`) || '{}');

        // Pastikan showQuestion dipanggil saat DOM siap
        document.addEventListener('DOMContentLoaded', function() {
            showQuestion(currentIndex);
            startTimer();
            syncAnswersFromServer();
        });

        function showQuestion(index) {
            questions.forEach(q => q.classList.add('hidden'));
            const currentQuestion = questions[index];
            if (currentQuestion) {
                currentQuestion.classList.remove('hidden');
                document.getElementById('current-question-number').textContent = index + 1;
                document.getElementById('prev-question').disabled = index === 0;
                document.getElementById('next-question').disabled = index === totalQuestions - 1;
                // showFeedback(index);
            } else {
                console.error('Question not found at index:', index);
            }
        }

        function goToQuestion(index) {
            currentIndex = index;
            showQuestion(currentIndex);
        }

        function showFeedback(index) {
            const questionId = questions[index].dataset.questionId;
            const feedbackDiv = document.getElementById(`feedback-${questionId}`);
            if (userAnswers[questionId]) {
                feedbackDiv.classList.remove('hidden');
                feedbackDiv.innerHTML = userAnswers[questionId].isCorrect
                    ? '<p class="text-green-600">Jawaban Anda benar!</p>'
                    : '<p class="text-red-600">Jawaban Anda salah.</p>';
            } else {
                feedbackDiv.classList.add('hidden');
            }
        }

        document.getElementById('prev-question').addEventListener('click', () => {
            saveAnswerAutomatically();
            if (currentIndex > 0) {
                currentIndex--;
                showQuestion(currentIndex);
            }
        });

        document.getElementById('next-question').addEventListener('click', () => {
            saveAnswerAutomatically();
            if (currentIndex < totalQuestions - 1) {
                currentIndex++;
                showQuestion(currentIndex);
            }
        });

        function saveAnswerAutomatically() {
            const currentQuestionId = questions[currentIndex].dataset.questionId;
            const selectedOption = document.querySelector(`input[name="answers[${currentQuestionId}]"]:checked`);
            if (selectedOption) {
                fetch('/quizzes/save-answer', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        quiz_id: quizId,
                        question_id: currentQuestionId,
                        option_id: selectedOption.value
                    })
                }).then(response => response.json())
                  .then(data => {
                      if (data.success) {
                          userAnswers[currentQuestionId] = {
                              value: selectedOption.value,
                              isCorrect: data.is_correct
                          };
                          localStorage.setItem(`quiz_answers_${quizId}`, JSON.stringify(userAnswers));
                          updateMapStatus(currentQuestionId);
                        //   showFeedback(currentIndex);
                      }
                  }).catch(error => {
                      console.error('AJAX error:', error);
                  });
            }
        }

        function updateMapStatus(questionId) {
            const buttons = document.querySelectorAll('#question-map button');
            buttons.forEach(btn => {
                if (btn.dataset.questionId == String(questionId) && userAnswers[questionId]) {
                    btn.classList.remove('secondary-button', 'text-gray-800');
                    btn.classList.add('primary-button', 'text-blue-800');
                }
            });
        }

        // Hitung mundur
        function startTimer() {
            const timer = document.getElementById('timer');
            const interval = setInterval(() => {
                const now = new Date().getTime();
                const distance = endTime - now;

                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                timer.textContent = `Waktu: ${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;

                if (distance < 0) {
                    clearInterval(interval);
                    timer.textContent = 'Waktu Habis';
                    document.getElementById('quiz-form').submit();
                }
            }, 1000);
        }

        // Modal Konfirmasi
        const confirmModal = document.getElementById('confirm-modal');
        const submitButton = document.getElementById('submit-button');
        const confirmSubmit = document.getElementById('confirm-submit');
        const cancelSubmit = document.getElementById('cancel-submit');

        submitButton.addEventListener('click', () => {
            confirmModal.classList.remove('hidden');
        });

        confirmSubmit.addEventListener('click', () => {
            confirmModal.classList.add('hidden');
            document.getElementById('quiz-form').submit();
        });

        cancelSubmit.addEventListener('click', () => {
            confirmModal.classList.add('hidden');
        });

        confirmModal.addEventListener('click', (e) => {
            if (e.target === confirmModal) {
                confirmModal.classList.add('hidden');
            }
        });

        // Sinkronisasi jawaban dari server
        function syncAnswersFromServer() {
            fetch('/quizzes/save-answer', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ quiz_id: quizId, get: true })
            }).then(response => response.json())
              .then(data => {
                  if (data.userAnswers) {
                      userAnswers = data.userAnswers;
                      localStorage.setItem(`quiz_answers_${quizId}`, JSON.stringify(userAnswers));
                      for (let [questionId, optionId] of Object.entries(userAnswers)) {
                          let input = document.querySelector(`input[name="answers[${questionId}]"][value="${optionId.value}"]`);
                          if (input) input.checked = true;
                          updateMapStatus(questionId);
                      }
                  }
              }).catch(error => {
                  console.error('Initial fetch error:', error);
              });
        }

        // Simpan jawaban ke session saat submit
        document.getElementById('quiz-form').addEventListener('submit', function(e) {
            let answers = {};
            document.querySelectorAll('input[name^="answers"]').forEach(input => {
                if (input.checked) {
                    answers[input.name.match(/\d+/)[0]] = input.value;
                }
            });
            fetch('/quizzes/save-answer', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ quiz_id: quizId, answers: answers, submit: true })
            }).then(() => {
                localStorage.removeItem(`quiz_answers_${quizId}`);
            });
        });
    </script>
@endsection