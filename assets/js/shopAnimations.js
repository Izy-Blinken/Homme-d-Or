
    document.addEventListener('DOMContentLoaded', function() {
        
    //rotating green animation
        
        const greetings = [
            { language: 'English', text: 'Welcome' },
            { language: 'Filipino', text: 'Maligayang pagdating' },
            { language: 'Japanese', text: 'ようこそ' },
            { language: 'French', text: 'Bienvenue' },
            { language: 'Chinese', text: '欢迎' }
        ];
        
        let currentIndex = 0;
        const greetingElement = document.querySelector('.greeting-text');
        
        function rotateGreeting() {
            // Fade out
            greetingElement.style.opacity = '0';
            greetingElement.style.transform = 'translateY(-20px)';
            
            setTimeout(() => {
                // Change text
                currentIndex = (currentIndex + 1) % greetings.length;
                greetingElement.textContent = greetings[currentIndex].text;
                
                // Fade in
                greetingElement.style.opacity = '1';
                greetingElement.style.transform = 'translateY(0)';
            }, 500); 
        }
        
        setTimeout(() => {
            rotateGreeting();
            setInterval(rotateGreeting, 3000);
        }, 2000);
        
        //Scroll to sections

        const categoryLinks = document.querySelectorAll('.shop-category-link');
        const sections = document.querySelectorAll('.shop-products-section');
        
        categoryLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href').substring(1);
                const targetSection = document.getElementById(targetId);
                
                if (targetSection) {
                    const targetPosition = targetSection.offsetTop;
                    const startPosition = window.pageYOffset;
                    const distance = targetPosition - startPosition;
                    const duration = 1000;
                    let start = null;
                    
                    function animateScroll(currentTime) {
                        if (start === null) start = currentTime;
                        const timeElapsed = currentTime - start;
                        const progress = Math.min(timeElapsed / duration, 1);
                        
                        const ease = progress < 0.5 
                            ? 4 * progress * progress * progress 
                            : 1 - Math.pow(-2 * progress + 2, 3) / 2;
                        
                        window.scrollTo(0, startPosition + (distance * ease));
                        
                        if (timeElapsed < duration) {
                            requestAnimationFrame(animateScroll);
                        } else {
                            window.scrollTo(0, targetPosition);
                        }
                    }
                    
                    requestAnimationFrame(animateScroll);
                }
            });
        });
        
        //Active link highlight
        
        function updateActiveLink() {
            let current = '';
            const scrollPosition = window.pageYOffset + 200;
            
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                
                if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                    current = section.getAttribute('id');
                }
            });
            
            categoryLinks.forEach(link => {
                link.classList.remove('active');
                const href = link.getAttribute('href');
                if (href === '#' + current) {
                    link.classList.add('active');
                }
            });
        }
        
        let scrollTimeout;
        window.addEventListener('scroll', () => {
            if (scrollTimeout) {
                window.cancelAnimationFrame(scrollTimeout);
            }
            scrollTimeout = window.requestAnimationFrame(() => {
                updateActiveLink();
            });
        });
        
        //Add to cart
        
        const addToCartButtons = document.querySelectorAll('.shop-add-to-cart');
        
        addToCartButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const productCard = this.closest('.shop-product-card');
                const productTitle = productCard.querySelector('.shop-product-title').textContent;
                const productPrice = productCard.querySelector('.shop-product-price').textContent;
                
                console.log('Product added to cart:', {
                    title: productTitle,
                    price: productPrice
                });
                
                const originalContent = this.innerHTML;
                
                this.innerHTML = '<i class="fas fa-check"></i><span class="cart-text">Added!</span>';
                this.style.background = '#4CAF50';
                this.style.borderColor = '#4CAF50';
                
                createFloatingNotification(productTitle);
                
                setTimeout(() => {
                    this.innerHTML = originalContent;
                    this.style.background = '';
                    this.style.borderColor = '';
                }, 1500);
            });
        });
        
        //floating animation
        
        function createFloatingNotification(productName) {
            const notification = document.createElement('div');
            notification.innerHTML = `
                <i class="fas fa-check-circle"></i>
                <span>${productName} added to cart</span>
            `;
            
            Object.assign(notification.style, {
                position: 'fixed',
                top: '20px',
                right: '20px',
                background: '#4CAF50',
                color: 'white',
                padding: '1rem 1.5rem',
                borderRadius: '8px',
                boxShadow: '0 8px 20px rgba(0,0,0,0.2)',
                zIndex: '10000',
                display: 'flex',
                alignItems: 'center',
                gap: '0.5rem',
                fontSize: '0.9rem',
                fontWeight: '500',
                opacity: '0',
                transform: 'translateY(-20px)',
                transition: 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)'
            });
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.opacity = '1';
                notification.style.transform = 'translateY(0)';
            }, 10);
            
            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transform = 'translateY(-20px)';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 400);
            }, 2500);
        }
        
        //Product card hover effects

        const productCards = document.querySelectorAll('.shop-product-card');
        
        productCards.forEach(card => {
            card.addEventListener('mousemove', function(e) {
                const rect = this.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                
                const tiltX = ((y - centerY) / centerY) * -2;
                const tiltY = ((x - centerX) / centerX) * 2;
                
                this.style.transform = `perspective(1000px) rotateX(${tiltX}deg) rotateY(${tiltY}deg) translateY(-8px)`;
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = '';
            });
        });
        
        // Discover button
        
        const discoverButtons = document.querySelectorAll('.shop-discover-btn');
        
        discoverButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.width = ripple.style.height = size + 'px';
                ripple.style.left = x + 'px';
                ripple.style.top = y + 'px';
                
                Object.assign(ripple.style, {
                    position: 'absolute',
                    borderRadius: '50%',
                    background: 'rgba(255, 255, 255, 0.6)',
                    transform: 'scale(0)',
                    animation: 'ripple-animation 0.6s ease-out',
                    pointerEvents: 'none'
                });
                
                this.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });
        
        updateActiveLink();
        
        console.log('Shop page enhanced animations loaded!');
    });
 