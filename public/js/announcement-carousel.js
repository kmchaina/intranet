document.addEventListener('DOMContentLoaded', function() {
    // Initialize both carousels (main dashboard and staff dashboard)
    const carousels = document.querySelectorAll('#announcement-carousel');
    
    carousels.forEach(carousel => {
        if (!carousel) return;
        
        const slides = carousel.children;
        const totalSlides = slides.length;
        
        if (totalSlides <= 1) return; // No need for carousel with one or no slides
        
        let currentSlide = 0;
        
        // Get navigation buttons and indicators for this carousel
        const prevBtn = carousel.parentElement.parentElement.querySelector('#prev-btn, #carousel-prev');
        const nextBtn = carousel.parentElement.parentElement.querySelector('#next-btn, #carousel-next');
        const indicators = carousel.parentElement.parentElement.querySelectorAll('.indicator, .carousel-indicator');
        
        function updateCarousel() {
            // Move carousel
            const translateX = -currentSlide * 100;
            carousel.style.transform = `translateX(${translateX}%)`;
            
            // Update indicators
            indicators.forEach((indicator, index) => {
                if (index === currentSlide) {
                    indicator.classList.remove('bg-gray-300', 'hover:bg-gray-400');
                    indicator.classList.add('bg-nimr-primary-500');
                } else {
                    indicator.classList.remove('bg-nimr-primary-500');
                    indicator.classList.add('bg-gray-300', 'hover:bg-gray-400');
                }
            });
        }
        
        function nextSlide() {
            currentSlide = (currentSlide + 1) % totalSlides;
            updateCarousel();
        }
        
        function prevSlide() {
            currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
            updateCarousel();
        }
        
        function goToSlide(index) {
            currentSlide = index;
            updateCarousel();
        }
        
        // Event listeners
        if (nextBtn) {
            nextBtn.addEventListener('click', nextSlide);
        }
        
        if (prevBtn) {
            prevBtn.addEventListener('click', prevSlide);
        }
        
        // Indicator event listeners
        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => goToSlide(index));
        });
        
        // Auto-advance carousel every 5 seconds
        setInterval(nextSlide, 5000);
        
        // Touch/swipe support for mobile
        let startX = 0;
        let endX = 0;
        
        carousel.addEventListener('touchstart', e => {
            startX = e.touches[0].clientX;
        });
        
        carousel.addEventListener('touchend', e => {
            endX = e.changedTouches[0].clientX;
            handleSwipe();
        });
        
        function handleSwipe() {
            const threshold = 50; // Minimum distance for swipe
            const diff = startX - endX;
            
            if (Math.abs(diff) > threshold) {
                if (diff > 0) {
                    nextSlide(); // Swipe left - go to next
                } else {
                    prevSlide(); // Swipe right - go to previous
                }
            }
        }
        
        // Keyboard navigation
        document.addEventListener('keydown', e => {
            if (e.key === 'ArrowLeft') {
                prevSlide();
            } else if (e.key === 'ArrowRight') {
                nextSlide();
            }
        });
        
        // Initialize carousel
        updateCarousel();
    });
});