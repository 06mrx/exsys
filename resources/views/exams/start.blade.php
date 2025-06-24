@extends('layouts.student')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800">{{ $exam->name }}</h2>
        <span id="timer" class="text-gray-600 font-semibold">Waktu: {{ $exam->duration }}:00</span>
    </div>
    <div class="neo-card p-6">
        @if ($questions->isEmpty())
            <p class="text-gray-600">Tidak ada soal untuk ujian ini.</p>
        @else
            <form method="POST" action="{{ route('exams.submit', $exam) }}" id="exam-form">
                @csrf
                <div class="mb-6">
                    <label for="current-question" class="text-gray-600">Soal: <span id="current-question-number">1</span>
                        dari {{ $questions->count() }}</label>
                </div>
                <div id="question-container">
                    @foreach ($questions as $index => $question)
                        <div class="question-item hidden" data-question-id="{{ $question->id }}"
                            data-question-type="{{ $question->question_type }}">
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">Soal {{ $index + 1 }}</h3>
                            <p class="text-gray-600 mb-4">{{ $question->content }}</p>
                            @if ($question->image_path)
                                <div class="mb-4">
                                    <img src="{{ asset('storage/' . $question->image_path) }}" alt="Gambar Soal"
                                        class="max-w-full h-auto rounded-lg shadow-neo-light">
                                </div>
                            @endif
                            @foreach ($question->options as $option)
                                <div class="flex items-center mb-2">
                                    @if ($question->question_type === 'multiple')
                                        <input type="checkbox" name="answers[{{ $question->id }}][]"
                                            value="{{ $option->id }}" id="option_{{ $option->id }}" class="neo-input"
                                            {{ isset($userAnswers[$question->id]) && in_array($option->id, (array) $userAnswers[$question->id]) ? 'checked' : '' }}>
                                    @else
                                        <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option->id }}"
                                            id="option_{{ $option->id }}" class="neo-input"
                                            {{ isset($userAnswers[$question->id]) && $userAnswers[$question->id] == $option->id ? 'checked' : '' }}>
                                    @endif
                                    <label for="option_{{ $option->id }}"
                                        class="ml-2 text-gray-600">{{ $option->content }}</label>
                                </div>
                            @endforeach
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
                                data-question-index="{{ $index }}" data-question-id="{{ $question->id }}"
                                onclick="goToQuestion({{ $index }})">
                                {{ $index + 1 }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Modal Konfirmasi -->
                <div id="confirm-modal"
                    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
                    <div class="neo-card p-6 max-w-sm w-full">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Konfirmasi Selesai Ujian</h3>
                        <p class="text-gray-600 mb-4">Apakah Anda yakin ingin mengakhiri ujian? Jawaban Anda akan disimpan
                            dan ujian akan selesai.</p>
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
        const examId = {{ $exam->id }};
        const endTime = '{{ $endTime }}';
        let userAnswers = JSON.parse(localStorage.getItem(`exam_answers_${examId}`) || '{}');
        let autoSubmit = false;

        function showQuestion(index) {
            questions.forEach(q => q.classList.add('hidden'));
            questions[index].classList.remove('hidden');
            document.getElementById('current-question-number').textContent = index + 1;
            document.getElementById('prev-question').disabled = index === 0;
            document.getElementById('next-question').disabled = index === totalQuestions - 1;
        }

        function goToQuestion(index) {
            currentIndex = index;
            saveAnswerAutomatically();
            showQuestion(currentIndex);
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
            const questionType = questions[currentIndex].dataset.questionType;
            const selectedOptions = document.querySelectorAll(`input[name="answers[${currentQuestionId}][]"]:checked`) || 
                                   document.querySelector(`input[name="answers[${currentQuestionId}]"]:checked`);

            if (selectedOptions) {
                let answers = [];
                if (questionType === 'multiple') {
                    selectedOptions.forEach(option => answers.push(option.value));
                } else {
                    answers = [selectedOptions.value];
                }
                userAnswers[currentQuestionId] = answers;
                localStorage.setItem(`exam_answers_${examId}`, JSON.stringify(userAnswers));
                console.log('Saving answer for question:', currentQuestionId, 'with value:', answers);

                // Kirim ke server via AJAX
                fetch('/exams/save-answer', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        exam_id: examId,
                        question_id: currentQuestionId,
                        option_ids: answers
                    })
                }).then(response => response.json())
                  .then(data => {
                      console.log('AJAX response:', data);
                      if (data.success) {
                          updateMapStatus(currentQuestionId);
                      } else {
                          console.error('Failed to save answer');
                      }
                  }).catch(error => {
                      console.error('AJAX error:', error);
                  });
            } else {
                console.log('No answer selected for question:', currentQuestionId);
                delete userAnswers[currentQuestionId]; // Hapus jika tidak ada jawaban
                localStorage.setItem(`exam_answers_${examId}`, JSON.stringify(userAnswers));
            }
        }

        function updateMapStatus(questionId) {
            const buttons = document.querySelectorAll('#question-map button');
            buttons.forEach(btn => {
                if (btn.dataset.questionId == String(questionId) && userAnswers[questionId]) {
                    btn.classList.remove('secondary-button', 'bg-gray-200', 'text-gray-800');
                    btn.classList.add('bg-blue-200', 'text-blue-800');
                }
            });
        }

        function getEndTime() {
            const savedEndTime = localStorage.getItem('endTime');
            if (savedEndTime) {
                return parseInt(savedEndTime);
            } else {
                localStorage.setItem('endTime', new Date(endTime).getTime());
                return new Date(endTime).getTime();
            }
        }

        function startTimer() {
            var endTime = getEndTime();
            // alert(endTime)
            if (isNaN(endTime)) {
                // alert('Waktu ujian tidak valid. Silakan muat ulang halaman.');
                 endTime = '{{ $endTime }}';
                // alert(endTime)
            }

            // alert(endTime)
            const timer = document.getElementById('timer');
            const interval = setInterval(() => {
                const now = new Date().getTime();
                const distance = endTime - now;

                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                timer.textContent = `Waktu: ${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;

                if (distance <= 0) {
                    clearInterval(interval);
                    timer.textContent = 'Waktu Habis';
                    localStorage.removeItem('endTime');
                    document.getElementById('exam-form').submit();
                }
            }, 1000);
        }

        const confirmModal = document.getElementById('confirm-modal');
        const submitButton = document.getElementById('submit-button');
        const confirmSubmit = document.getElementById('confirm-submit');
        const cancelSubmit = document.getElementById('cancel-submit');

        submitButton.addEventListener('click', () => {
            confirmModal.classList.remove('hidden');
        });

        confirmSubmit.addEventListener('click', () => {
            confirmModal.classList.add('hidden');
            document.getElementById('exam-form').submit();
        });

        cancelSubmit.addEventListener('click', () => {
            confirmModal.classList.add('hidden');
        });

        confirmModal.addEventListener('click', (e) => {
            if (e.target === confirmModal) {
                confirmModal.classList.add('hidden');
            }
        });

        window.onload = function() {
            fetch('/exams/save-answer', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    exam_id: examId,
                    get: true
                })
            }).then(response => response.json())
              .then(data => {
                  console.log('Initial fetch response:', data);
                  if (data.userAnswers) {
                      userAnswers = data.userAnswers;
                      localStorage.setItem(`exam_answers_${examId}`, JSON.stringify(userAnswers));
                      for (let [questionId, optionIds] of Object.entries(userAnswers)) {
                          let inputs = document.querySelectorAll(`input[name="answers[${questionId}][]"]`) || 
                                       document.querySelectorAll(`input[name="answers[${questionId}]"]`);
                          inputs.forEach(input => {
                              if (Array.isArray(optionIds) ? optionIds.includes(input.value) : input.value === optionIds) {
                                  input.checked = true;
                              }
                              updateMapStatus(questionId);
                          });
                      }
                  }
                  showQuestion(currentIndex);
                  startTimer();
              }).catch(error => {
                  console.error('Initial fetch error:', error);
              });
        };

        document.getElementById('exam-form').addEventListener('submit', function(e) {
            if (!autoSubmit) {
                e.preventDefault();
            }
            let answers = {};
            document.querySelectorAll('input[name^="answers"]').forEach(input => {
                if (input.checked) {
                    if (!answers[input.name.match(/\d+/)[0]]) {
                        answers[input.name.match(/\d+/)[0]] = [];
                    }
                    answers[input.name.match(/\d+/)[0]].push(input.value);
                }
            });
            fetch('/exams/save-answer', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    exam_id: examId,
                    answers: answers,
                    submit: true
                })
            }).then(() => {
                localStorage.removeItem(`exam_answers_${examId}`);
                if (!autoSubmit) {
                    document.getElementById('exam-form').submit();
                }
            });
        });
    </script>
@endsection