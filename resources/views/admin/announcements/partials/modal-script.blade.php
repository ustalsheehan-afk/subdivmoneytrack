<script>
    function openModal(id, title, content, date, category, accentColor, iconClass, imagePath = null) {
        const modalTitle = document.getElementById('modal-title');
        const modalContent = document.getElementById('modal-content');
        const modalDate = document.getElementById('modal-date');
        const modalCategory = document.getElementById('modal-category');
        const iconBox = document.getElementById('modal-icon-box');
        const modalIcon = document.getElementById('modal-icon');
        const modalBar = document.getElementById('modal-bar');
        const modalImageContainer = document.getElementById('modal-image-container');
        const modalImage = document.getElementById('modal-image');

        if(modalTitle) modalTitle.innerText = title;
        if(modalContent) modalContent.innerHTML = content;
        if(modalDate) modalDate.innerText = date;
        if(modalCategory) modalCategory.innerText = category;
        
        // Update category styles
        if(iconBox) {
            iconBox.style.backgroundColor = accentColor + '20'; // 20% opacity
            iconBox.style.color = accentColor;
        }
        if(modalIcon) modalIcon.className = 'bi ' + iconClass + ' text-xl';
        if(modalBar) modalBar.style.backgroundColor = accentColor;

        // Handle Image
        if (imagePath && modalImage && modalImageContainer) {
            modalImage.src = '{{ asset("storage") }}/' + imagePath;
            modalImageContainer.classList.remove('hidden');
        } else if (modalImageContainer) {
            modalImageContainer.classList.add('hidden');
        }

        const modal = document.getElementById('announcement-modal');
        const panel = document.getElementById('modal-panel');
        
        if(modal && panel) {
            modal.classList.remove('invisible', 'opacity-0');
            panel.classList.remove('scale-95', 'opacity-0');
            panel.classList.add('scale-100', 'opacity-100');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeModal() {
        const modal = document.getElementById('announcement-modal');
        const panel = document.getElementById('modal-panel');
        
        if(modal && panel) {
            modal.classList.add('invisible', 'opacity-0');
            panel.classList.remove('scale-100', 'opacity-100');
            panel.classList.add('scale-95', 'opacity-0');
        }
        
        setTimeout(() => {
            document.body.style.overflow = 'auto';
        }, 300);
    }

    // Close on click outside
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('announcement-modal');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeModal();
                }
            });
        }
    });
</script>