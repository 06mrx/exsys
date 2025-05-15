import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    // Sidebar toggle
    const sidebar = document.getElementById('sidebar');
    const toggleButton = document.getElementById('sidebar-toggle');
    const sidebarCloseButton = document.getElementById('sidebar-close');

    if (toggleButton && sidebar && sidebarCloseButton) {
        toggleButton.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
        });

        sidebarCloseButton.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
        });
    }

    // Modal close (pesan sukses/error)
    const messageModal = document.getElementById('message-modal');
    const messageModalCloseButton = document.getElementById('modal-close');

    if (messageModal && messageModalCloseButton) {
        messageModalCloseButton.addEventListener('click', () => {
            messageModal.classList.add('hidden');
        });

        messageModal.addEventListener('click', (e) => {
            if (e.target === messageModal) {
                messageModal.classList.add('hidden');
            }
        });
    }

    // Modal hapus
    const deleteModal = document.getElementById('delete-modal');
    const deleteModalCloseButton = document.getElementById('delete-modal-close');
    const deleteModalCancelButton = document.getElementById('delete-modal-cancel');
    const deleteForm = document.getElementById('delete-form');

    if (deleteModal && deleteModalCloseButton && deleteModalCancelButton && deleteForm) {
        // Fungsi untuk membuka modal dan mengatur URL form
        window.openDeleteModal = function (userId) {
            deleteForm.action = `/users/${userId}`;
            deleteModal.classList.remove('hidden');
        };

        // Tutup modal saat tombol close diklik
        deleteModalCloseButton.addEventListener('click', () => {
            deleteModal.classList.add('hidden');
        });

        // Tutup modal saat tombol batal diklik
        deleteModalCancelButton.addEventListener('click', () => {
            deleteModal.classList.add('hidden');
        });

        // Tutup modal saat mengklik overlay
        deleteModal.addEventListener('click', (e) => {
            if (e.target === deleteModal) {
                deleteModal.classList.add('hidden');
            }
        });
    }
});