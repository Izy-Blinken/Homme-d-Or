function playHeroVideo() {
    window.open('https://www.youtube.com/watch?v=YOUR_VIDEO_ID', '_blank');
}

// Single Video Seamless Loop
document.addEventListener('DOMContentLoaded', function() {
    const video = document.getElementById('hero-video-bg');
    
    if (video) {
        video.addEventListener('timeupdate', function() {
            if (video.currentTime >= video.duration - 0.05) {
                video.currentTime = 0;
            }
        });
        video.playbackRate = 1.0;
        video.play().catch(error => console.log('Autoplay failed:', error));
    }
    
    // carousel and products functionality
    let currentIndex = 0;
    const track = document.getElementById('carouselTrack');
    const slides = document.querySelectorAll('.carousel-slide');
    const dotsContainer = document.getElementById('carouselDots');
    const fade = document.querySelectorAll('.fade-in');

    //carousel fade effect
    const appearOptions= { threshold: 0.1, rootMargin: "0px -50px" };
    const appearOnScroll = new IntersectionObserver(function(entries, appearOnScroll) {
        entries.forEach(entry => {
            if (!entry.isIntersecting) {
                entry.target.classList.remove('appear');
            } else {
                entry.target.classList.add('appear');
            }
        });
    }, appearOptions);

    fade.forEach(fade => {
        appearOnScroll.observe(fade);
    });

    //create dots
    slides.forEach((_, index) => {
        const dot = document.createElement('div');
        dot.classList.add('carousel-dot');
        if (index === 0) dot.classList.add('active');
        dot.addEventListener('click', () => goToSlide(index));
        dotsContainer.appendChild(dot);
    });

    const dots = document.querySelectorAll('.carousel-dot');

    window.moveCarousel = function(direction) {
        currentIndex += direction;
        if (currentIndex >= slides.length) currentIndex = 0;
        if (currentIndex < 0) currentIndex = slides.length - 1;
        updateCarousel();
    }

    function goToSlide(index) {
        currentIndex = index;
        updateCarousel();
    }

    function updateCarousel() {
        const offset = -currentIndex * 100;
        track.style.transform = `translateX(${offset}%)`;
        dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === currentIndex);
        });
    }

    setInterval(() => moveCarousel(1), 5000);

    // Wishlist
    document.querySelectorAll('.wishlist-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            this.classList.toggle('active');
        });
    });
    
    // FIRST carousel — reads PHP-rendered cards, groups them into sets of 4
    const arrivalsGrid = document.getElementById('arrivalsGrid');
    const nextBtn = document.getElementById('nextBtn');
    const indicatorsContainer = document.getElementById('lineIndicators');

    if (arrivalsGrid && nextBtn && indicatorsContainer) {
        // Grab all cards PHP already rendered
        const allCards = Array.from(arrivalsGrid.querySelectorAll('.new-arrival-card'));
        const cardsPerSet = 4;

        // Split into sets of 4
        const sets = [];
        for (let i = 0; i < allCards.length; i += cardsPerSet) {
            sets.push(allCards.slice(i, i + cardsPerSet));
        }

        // If no products, just hide the next button and indicators
        if (sets.length === 0) {
            nextBtn.style.display = 'none';
            indicatorsContainer.style.display = 'none';
        } else {
            let currentIndex = 0;
            const indicators = indicatorsContainer.querySelectorAll('.indicator');

            function updateNewArrivals() {
                // Hide all cards first
                allCards.forEach(card => card.style.display = 'none');

                // Fade out
                arrivalsGrid.style.opacity = '0';
                arrivalsGrid.style.transform = 'translateX(30px)';

                setTimeout(() => {
                    // Show only current set's cards
                    sets[currentIndex].forEach(card => card.style.display = '');

                    // Update indicators
                    indicators.forEach((indicator, i) => {
                        indicator.classList.toggle('active', i === currentIndex);
                    });

                    // Fade in
                    setTimeout(() => {
                        arrivalsGrid.style.opacity = '1';
                        arrivalsGrid.style.transform = 'translateX(0)';
                    }, 50);
                }, 300);
            }

            nextBtn.addEventListener('click', function () {
                currentIndex++;
                if (currentIndex >= sets.length) currentIndex = 0;
                updateNewArrivals();
            });

            indicators.forEach((indicator, i) => {
                indicator.addEventListener('click', function () {
                    currentIndex = i;
                    updateNewArrivals();
                });
            });

            // Initial display
            updateNewArrivals();
        }
    }
    
    // SECOND carousel — same approach, reads PHP-rendered cards
    const arrivalsGrid2 = document.getElementById('arrivalsGrid2');
    const nextBtn2 = document.getElementById('nextBtn2');
    const indicatorsContainer2 = document.getElementById('lineIndicators2');

    if (arrivalsGrid2 && nextBtn2 && indicatorsContainer2) {
        const allCards2 = Array.from(arrivalsGrid2.querySelectorAll('.new-arrival-card'));
        const cardsPerSet2 = 4;

        const sets2 = [];
        for (let i = 0; i < allCards2.length; i += cardsPerSet2) {
            sets2.push(allCards2.slice(i, i + cardsPerSet2));
        }

        if (sets2.length === 0) {
            nextBtn2.style.display = 'none';
            indicatorsContainer2.style.display = 'none';
        } else {
            let currentIndex2 = 0;
            const indicators2 = indicatorsContainer2.querySelectorAll('.indicator');

            function updateNewArrivals2() {
                allCards2.forEach(card => card.style.display = 'none');

                arrivalsGrid2.style.opacity = '0';
                arrivalsGrid2.style.transform = 'translateX(30px)';

                setTimeout(() => {
                    sets2[currentIndex2].forEach(card => card.style.display = '');

                    indicators2.forEach((indicator, i) => {
                        indicator.classList.toggle('active', i === currentIndex2);
                    });

                    setTimeout(() => {
                        arrivalsGrid2.style.opacity = '1';
                        arrivalsGrid2.style.transform = 'translateX(0)';
                    }, 50);
                }, 300);
            }

            nextBtn2.addEventListener('click', function () {
                currentIndex2++;
                if (currentIndex2 >= sets2.length) currentIndex2 = 0;
                updateNewArrivals2();
            });

            indicators2.forEach((indicator, i) => {
                indicator.addEventListener('click', function () {
                    currentIndex2 = i;
                    updateNewArrivals2();
                });
            });

            updateNewArrivals2();
        }
    } else {
        console.error('Second carousel elements not found:', {
            arrivalsGrid2: !!arrivalsGrid2,
            nextBtn2: !!nextBtn2,
            indicatorsContainer2: !!indicatorsContainer2
        });
    }
});


//testimonials carousel
document.addEventListener('DOMContentLoaded', function() {
    const testimonialsTrack = document.getElementById('testimonialsTrack');
    const prevBtn = document.getElementById('testimonialPrev');
    const nextBtn = document.getElementById('testimonialNext');
    
    if (testimonialsTrack && prevBtn && nextBtn) {
        const cards = testimonialsTrack.querySelectorAll('.testimonial-card');
        let currentPosition = 0;
        let cardsToShow = 3; 
        
        // Update cards to show based on screen width
        function updateCardsToShow() {
            if (window.innerWidth <= 768) {
                cardsToShow = 1;
            } else if (window.innerWidth <= 1024) {
                cardsToShow = 2;
            } else {
                cardsToShow = 3;
            }
        }
        
        function getMaxPosition() {
            return cards.length - cardsToShow;
        }
        
        // Update testimonials position
        function updateTestimonials() {
            const cardWidth = cards[0].offsetWidth;
            const gap = 30; 
            const offset = -(currentPosition * (cardWidth + gap));
            testimonialsTrack.style.transform = `translateX(${offset}px)`;
            
            // Update button states
            prevBtn.disabled = currentPosition === 0;
            nextBtn.disabled = currentPosition >= getMaxPosition();
            
            if (prevBtn.disabled) {
                prevBtn.style.opacity = '0.5';
                prevBtn.style.cursor = 'not-allowed';
            } else {
                prevBtn.style.opacity = '1';
                prevBtn.style.cursor = 'pointer';
            }
            
            if (nextBtn.disabled) {
                nextBtn.style.opacity = '0.5';
                nextBtn.style.cursor = 'not-allowed';
            } else {
                nextBtn.style.opacity = '1';
                nextBtn.style.cursor = 'pointer';
            }
        }
        
        nextBtn.addEventListener('click', function() {
            if (currentPosition < getMaxPosition()) {
                currentPosition++;
                updateTestimonials();
            }
        });
        
        prevBtn.addEventListener('click', function() {
            if (currentPosition > 0) {
                currentPosition--;
                updateTestimonials();
            }
        });
        
        // Handle window resize
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                updateCardsToShow();

                // Reset position if it's out of bounds
                if (currentPosition > getMaxPosition()) {
                    currentPosition = getMaxPosition();
                }
                updateTestimonials();
            }, 250);
        });
        
        updateCardsToShow();
        updateTestimonials();
        
        console.log('Testimonials carousel initialized successfully!');
    } else {
        console.error('Testimonials carousel elements not found');
    }
});



