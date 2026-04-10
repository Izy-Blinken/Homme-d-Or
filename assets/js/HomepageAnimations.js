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

    // carousel fade effect
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

    // create dots
    if (slides.length > 0 && dotsContainer) {
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
            if (track) track.style.transform = `translateX(${offset}%)`;
            dots.forEach((dot, index) => {
                dot.classList.toggle('active', index === currentIndex);
            });
        }

        setInterval(() => moveCarousel(1), 5000);
    }

    // Wishlist
    document.querySelectorAll('.wishlist-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            this.classList.toggle('active');
        });
    });
    

    // Helper function to initialize any horizontal carousel with dynamic indicators
    function setupHorizontalCarousel(scrollAreaId, prevBtnId, nextBtnId, indicatorsId) {
        const scrollArea = document.getElementById(scrollAreaId);
        const prevBtn = document.getElementById(prevBtnId);
        const nextBtn = document.getElementById(nextBtnId);
        const indicatorsContainer = document.getElementById(indicatorsId);

        if (scrollArea && prevBtn && nextBtn) {
            
            // 1. Calculate how far one arrow click should scroll
            const getScrollAmount = () => {
                if (scrollArea.children.length > 0) {
                    const gap = parseFloat(window.getComputedStyle(scrollArea).gap) || 0;
                    return scrollArea.children[0].offsetWidth + gap;
                }
                return 300; // Fallback
            };

            nextBtn.addEventListener('click', () => {
                scrollArea.scrollBy({ left: getScrollAmount(), behavior: 'smooth' });
            });

            prevBtn.addEventListener('click', () => {
                scrollArea.scrollBy({ left: -getScrollAmount(), behavior: 'smooth' });
            });

            // 2. Dynamic Line Indicators Logic
            if (indicatorsContainer) {
                const updateIndicators = () => {
                    const { scrollWidth, clientWidth, scrollLeft } = scrollArea;
                    const maxScroll = scrollWidth - clientWidth;
                    
                    // Hide indicators if no scrolling is needed (e.g. big screen, few items)
                    if (maxScroll <= 0) {
                        indicatorsContainer.style.display = 'none';
                        return;
                    }
                    indicatorsContainer.style.display = 'flex';

                    // Calculate how many "pages" we have
                    const pages = Math.ceil(scrollWidth / clientWidth);
                    
                    // Generate the lines if they don't match the current screen size
                    if (indicatorsContainer.children.length !== pages) {
                        indicatorsContainer.innerHTML = '';
                        for (let i = 0; i < pages; i++) {
                            const dot = document.createElement('div');
                            dot.className = 'line-indicator';
                            // Make indicators clickable to jump to that section
                            dot.addEventListener('click', () => {
                                scrollArea.scrollTo({
                                    left: (maxScroll / (pages - 1)) * i,
                                    behavior: 'smooth'
                                });
                            });
                            indicatorsContainer.appendChild(dot);
                        }
                    }

                    // Highlight the active line based on exact scroll progress
                    let activeIndex = Math.round((scrollLeft / maxScroll) * (pages - 1));
                    if (activeIndex < 0) activeIndex = 0;
                    if (activeIndex >= pages) activeIndex = pages - 1;

                    Array.from(indicatorsContainer.children).forEach((dot, index) => {
                        dot.classList.toggle('active', index === activeIndex);
                    });
                };

                // Update indicators when user scrolls (swipes) or resizes the window
                scrollArea.addEventListener('scroll', updateIndicators);
                window.addEventListener('resize', updateIndicators);
                
                // Initial load calculation (small delay ensures CSS is loaded)
                setTimeout(updateIndicators, 150);
            }
        }
    }

    // Initialize all 3 carousels on the homepage with their corresponding indicator IDs
    setupHorizontalCarousel('featScrollArea', 'featPrev', 'featNext', 'featIndicators');
    setupHorizontalCarousel('newArrScrollArea', 'newArrPrev', 'newArrNext', 'newArrIndicators');
    setupHorizontalCarousel('selScrollArea', 'selPrev', 'selNext', 'selIndicators');
});


// testimonials carousel
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


document.addEventListener('DOMContentLoaded', function() {
        // Check the URL to see if the bouncer kicked them here
        const urlParams = new URLSearchParams(window.location.search);
        
        if (urlParams.get('login_required') === 'true') {
            // Clean the URL so the modal doesn't keep opening if they refresh the page
            window.history.replaceState({}, document.title, window.location.pathname);
            
            // Open your existing login modal
            if (typeof openLoginModal === 'function') {
                openLoginModal();
                
                // Show a toast notification explaining why
                if (typeof showGeneralToast === 'function') {
                    showGeneralToast('Please log in or create an account to continue.', 'info');
                }
            }
        }
    });

// AJAX ADD TO CART FUNCTION
window.addToCart = function(productId) {
    // Package the data to send to the server
    const formData = new FormData();
    formData.append('product_id', productId);
    formData.append('quantity', 1);

    fetch('../backend/add_to_cart.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // It worked! Show the success toast
            showGeneralToast(data.message, 'success');
            
            // Note: If you have a little cart icon with a number counter,
            // you could trigger a function here to update that number!
            
        } else {
            // It failed (e.g., they are a stranger)
            showGeneralToast(data.message, 'error');
            
            // Automatically pop open the login/signup modal for strangers
            if (data.message.includes('login') || data.message.includes('guest')) {
                if (typeof openLoginModal === 'function') {
                    openLoginModal();
                }
            }
        }
    })
    .catch(error => {
        console.error('Error adding to cart:', error);
        showGeneralToast('Connection error. Please try again.', 'error');
    });
};