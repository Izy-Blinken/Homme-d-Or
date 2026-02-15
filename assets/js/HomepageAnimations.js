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
    
    // Product sets for New Arrivals (4 products per set)
    const newArrivalSets = [
        // Set 1
        [
            { 
                img: '../assets/images/brand_images/evrland.jpg', 
                name: 'Desert Blaze',
                description: 'WOODY · SPICY GOURMAND'
            },
            { 
                img: '../assets/images/brand_images/evrland.jpg', 
                name: 'Ocean Mist',
                description: 'FRESH · AQUATIC MARINE'
            },
            { 
                img: '../assets/images/brand_images/evrland.jpg', 
                name: 'Golden Night',
                description: 'AMBER · WARM ORIENTAL'
            },
            { 
                img: '../assets/images/brand_images/evrland.jpg', 
                name: 'Amber Woods',
                description: 'WOODY · EARTHY DEEP'
            }
        ],
        // Set 2
        [
            { 
                img: '../assets/images/brand_images/nocturne.png', 
                name: 'Mystic Azure',
                description: 'FLORAL · ELEGANT BLOOM'
            },
            { 
                img: '../assets/images/brand_images/nocturne.png', 
                name: 'Crimson Passion',
                description: 'SPICY · BOLD INTENSE'
            },
            { 
                img: '../assets/images/brand_images/nocturne.png', 
                name: 'Velvet Eclipse',
                description: 'WOODY · DARK MYSTERIOUS'
            },
            { 
                img: '../assets/images/brand_images/nocturne.png', 
                name: 'Silver Moon',
                description: 'POWDERY · SOFT ELEGANT'
            }
        ],
        // Set 3
        [
            { 
                img: '../assets/images/brand_images/sampleperfume.png', 
                name: 'Summer Breeze',
                description: 'CITRUS · LIGHT FRESH'
            },
            { 
                img: '../assets/images/brand_images/sampleperfume.png', 
                name: 'Midnight Rose',
                description: 'FLORAL · ROMANTIC CLASSIC'
            },
            { 
                img: '../assets/images/brand_images/sampleperfume.png', 
                name: 'Royal Silk',
                description: 'POWDERY · SOFT LUXURY'
            },
            { 
                img: '../assets/images/brand_images/sampleperfume.png', 
                name: 'Winter Frost',
                description: 'FRESH · COOL CRISP'
            }
        ]
    ];

    // Initialize FIRST carousel only if elements exist
    const arrivalsGrid = document.getElementById('arrivalsGrid');
    const nextBtn = document.getElementById('nextBtn');
    const indicatorsContainer = document.getElementById('lineIndicators');
    
    if (arrivalsGrid && nextBtn && indicatorsContainer) {
        let currentArrivalsIndex = 0;
        const indicators = indicatorsContainer.querySelectorAll('.indicator');

        // Function to create New Arrival card HTML
        function createArrivalCard(product) {
            return `
                <div class="new-arrival-card">
                    <button class="new-arrival-image" onclick="window.location.href='productdetails.php'">
                        <img src="${product.img}" alt="${product.name}">
                        <div class="arrival-overlay">
                            <p class="arrival-description">${product.description}</p>
                            <h3 class="arrival-name">${product.name}</h3>
                        </div>
                    </button>
                    <button class="arrival-add-cart" onclick="showGeneralToast('Added to cart!', 'info')">ADD TO CART</button>
                </div>
            `;
        }

        // Function to update New Arrivals display
        function updateNewArrivals() {
            const currentSet = newArrivalSets[currentArrivalsIndex];
            
            arrivalsGrid.style.opacity = '0';
            arrivalsGrid.style.transform = 'translateX(30px)';
            
            setTimeout(() => {
                // Update content
                arrivalsGrid.innerHTML = currentSet.map(product => createArrivalCard(product)).join('');
                
                // Update indicators
                indicators.forEach((indicator, index) => {
                    indicator.classList.toggle('active', index === currentArrivalsIndex);
                });
                
                // Fade in
                setTimeout(() => {
                    arrivalsGrid.style.opacity = '1';
                    arrivalsGrid.style.transform = 'translateX(0)';
                }, 50);
            }, 300);
        }

        nextBtn.addEventListener('click', function() {
            currentArrivalsIndex++;
            if (currentArrivalsIndex >= newArrivalSets.length) {
                currentArrivalsIndex = 0;
            }
            updateNewArrivals();
        });

        // Indicator click
        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', function() {
                currentArrivalsIndex = index;
                updateNewArrivals();
            });
        });

        // Initial load
        updateNewArrivals();
    }
    
    // Product sets for Second New Arrivals (4 products per set)
    const newArrivalSets2 = [
        // Set 1
        [
            { 
                img: '../assets/images/brand_images/evrland.jpg', 
                name: 'Desert Blaze 2',
                description: 'WOODY · SPICY GOURMAND'
            },
            { 
                img: '../assets/images/brand_images/evrland.jpg', 
                name: 'Ocean Mist 2',
                description: 'FRESH · AQUATIC MARINE'
            },
            { 
                img: '../assets/images/brand_images/evrland.jpg', 
                name: 'Golden Night 2',
                description: 'AMBER · WARM ORIENTAL'
            },
            { 
                img: '../assets/images/brand_images/evrland.jpg', 
                name: 'Amber Woods 2',
                description: 'WOODY · EARTHY DEEP'
            }
        ],
        // Set 2
        [
            { 
                img: '../assets/images/brand_images/nocturne.png', 
                name: 'Mystic Azure 2',
                description: 'FLORAL · ELEGANT BLOOM'
            },
            { 
                img: '../assets/images/brand_images/nocturne.png', 
                name: 'Crimson Passion 2',
                description: 'SPICY · BOLD INTENSE'
            },
            { 
                img: '../assets/images/brand_images/nocturne.png', 
                name: 'Velvet Eclipse 2',
                description: 'WOODY · DARK MYSTERIOUS'
            },
            { 
                img: '../assets/images/brand_images/nocturne.png', 
                name: 'Silver Moon 2',
                description: 'POWDERY · SOFT ELEGANT'
            }
        ],
        // Set 3
        [
            { 
                img: '../assets/images/brand_images/sampleperfume.png', 
                name: 'Summer Breeze 2',
                description: 'CITRUS · LIGHT FRESH'
            },
            { 
                img: '../assets/images/brand_images/sampleperfume.png', 
                name: 'Midnight Rose 2',
                description: 'FLORAL · ROMANTIC CLASSIC'
            },
            { 
                img: '../assets/images/brand_images/sampleperfume.png', 
                name: 'Royal Silk 2',
                description: 'POWDERY · SOFT LUXURY'
            },
            { 
                img: '../assets/images/brand_images/sampleperfume.png', 
                name: 'Winter Frost 2',
                description: 'FRESH · COOL CRISP'
            }
        ]
    ];

    // Initialize SECOND carousel only if elements exist
    const arrivalsGrid2 = document.getElementById('arrivalsGrid2');
    const nextBtn2 = document.getElementById('nextBtn2');
    const indicatorsContainer2 = document.getElementById('lineIndicators2');
    
    if (arrivalsGrid2 && nextBtn2 && indicatorsContainer2) {
        let currentArrivalsIndex2 = 0;
        const indicators2 = indicatorsContainer2.querySelectorAll('.indicator');

        // Function to create New Arrival card HTML
        function createArrivalCard2(product) {
            return `
                <div class="new-arrival-card" >
                    <button class="new-arrival-image" onclick="window.location.href='productdetails.php'">
                        <img src="${product.img}" alt="${product.name}">
                        <div class="arrival-overlay">
                            <p class="arrival-description">${product.description}</p>
                            <h3 class="arrival-name">${product.name}</h3>
                        </div>
                    </button>
                    <button class="arrival-add-cart" onclick="showGeneralToast('Added to cart!', 'info')">ADD TO CART</button>
                </div>
            `;
        }

        // Function to update New Arrivals display (Second Instance)
        function updateNewArrivals2() {
            const currentSet2 = newArrivalSets2[currentArrivalsIndex2];
            
            // Fade out
            arrivalsGrid2.style.opacity = '0';
            arrivalsGrid2.style.transform = 'translateX(30px)';
            
            setTimeout(() => {
                arrivalsGrid2.innerHTML = currentSet2.map(product => createArrivalCard2(product)).join('');
                
                // Update indicators
                indicators2.forEach((indicator, index) => {
                    indicator.classList.toggle('active', index === currentArrivalsIndex2);
                });
                
                setTimeout(() => {
                    arrivalsGrid2.style.opacity = '1';
                    arrivalsGrid2.style.transform = 'translateX(0)';
                }, 50);
            }, 300);
        }

        nextBtn2.addEventListener('click', function() {
            currentArrivalsIndex2++;
            if (currentArrivalsIndex2 >= newArrivalSets2.length) {
                currentArrivalsIndex2 = 0;
            }
            updateNewArrivals2();
        });

        indicators2.forEach((indicator, index) => {
            indicator.addEventListener('click', function() {
                currentArrivalsIndex2 = index;
                updateNewArrivals2();
            });
        });

        // Initial load 
        updateNewArrivals2();
        
        console.log('Second carousel initialized successfully!');
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



