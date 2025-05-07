/**
* Template Name: Gp
* Template URL: https://bootstrapmade.com/gp-free-multipurpose-html-bootstrap-template/
* Updated: Aug 15 2024 with Bootstrap v5.3.3
* Author: BootstrapMade.com
* License: https://bootstrapmade.com/license/
*/

(function() {
  "use strict";

  /**
   * Apply .scrolled class to the body as the page is scrolled down
   */
  function toggleScrolled() {
    const selectBody = document.querySelector('body');
    const selectHeader = document.querySelector('#header');
    if (!selectHeader.classList.contains('scroll-up-sticky') && !selectHeader.classList.contains('sticky-top') && !selectHeader.classList.contains('fixed-top')) return;
    window.scrollY > 100 ? selectBody.classList.add('scrolled') : selectBody.classList.remove('scrolled');
  }

  document.addEventListener('scroll', toggleScrolled);
  window.addEventListener('load', toggleScrolled);

  /**
   * Mobile nav toggle
   */
  const mobileNavToggleBtn = document.querySelector('.mobile-nav-toggle');

  function mobileNavToogle() {
    document.querySelector('body').classList.toggle('mobile-nav-active');
    mobileNavToggleBtn.classList.toggle('bi-list');
    mobileNavToggleBtn.classList.toggle('bi-x');
  }
  if (mobileNavToggleBtn) {
    mobileNavToggleBtn.addEventListener('click', mobileNavToogle);
  }

  /**
   * Hide mobile nav on same-page/hash links
   */
  document.querySelectorAll('#navmenu a').forEach(navmenu => {
    navmenu.addEventListener('click', () => {
      if (document.querySelector('.mobile-nav-active')) {
        mobileNavToogle();
      }
    });

  });

  /**
   * Toggle mobile nav dropdowns
   */
  document.querySelectorAll('.navmenu .toggle-dropdown').forEach(navmenu => {
    navmenu.addEventListener('click', function(e) {
      e.preventDefault();
      this.parentNode.classList.toggle('active');
      this.parentNode.nextElementSibling.classList.toggle('dropdown-active');
      e.stopImmediatePropagation();
    });
  });

  /**
   * Preloader
   */
  const preloader = document.querySelector('#preloader');
  if (preloader) {
    window.addEventListener('load', () => {
      preloader.remove();
    });
  }

  /**
   * Scroll top button
   */
  let scrollTop = document.querySelector('.scroll-top');

  function toggleScrollTop() {
    if (scrollTop) {
      window.scrollY > 100 ? scrollTop.classList.add('active') : scrollTop.classList.remove('active');
    }
  }
  scrollTop.addEventListener('click', (e) => {
    e.preventDefault();
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  });

  window.addEventListener('load', toggleScrollTop);
  document.addEventListener('scroll', toggleScrollTop);

  /**
   * Animation on scroll function and init
   */
  function aosInit() {
    AOS.init({
      duration: 600,
      easing: 'ease-in-out',
      once: true,
      mirror: false
    });
  }
  window.addEventListener('load', aosInit);

  /**
   * Init swiper sliders
   */
  function initSwiper() {
    document.querySelectorAll(".init-swiper").forEach(function(swiperElement) {
      let config = JSON.parse(
        swiperElement.querySelector(".swiper-config").innerHTML.trim()
      );

      if (swiperElement.classList.contains("swiper-tab")) {
        initSwiperWithCustomPagination(swiperElement, config);
      } else {
        new Swiper(swiperElement, config);
      }
    });
  }

  window.addEventListener("load", initSwiper);

  /**
   * Initiate glightbox
   */
  const glightbox = GLightbox({
    selector: '.glightbox'
  });

  /**
   * Init isotope layout and filters
   */
  document.querySelectorAll('.isotope-layout').forEach(function(isotopeItem) {
    let layout = isotopeItem.getAttribute('data-layout') ?? 'masonry';
    let filter = isotopeItem.getAttribute('data-default-filter') ?? '*';
    let sort = isotopeItem.getAttribute('data-sort') ?? 'original-order';

    let initIsotope;
    imagesLoaded(isotopeItem.querySelector('.isotope-container'), function() {
      initIsotope = new Isotope(isotopeItem.querySelector('.isotope-container'), {
        itemSelector: '.isotope-item',
        layoutMode: layout,
        filter: filter,
        sortBy: sort
      });
    });

    isotopeItem.querySelectorAll('.isotope-filters li').forEach(function(filters) {
      filters.addEventListener('click', function() {
        isotopeItem.querySelector('.isotope-filters .filter-active').classList.remove('filter-active');
        this.classList.add('filter-active');
        initIsotope.arrange({
          filter: this.getAttribute('data-filter')
        });
        if (typeof aosInit === 'function') {
          aosInit();
        }
      }, false);
    });

  });
  
  

  /**
   * Initiate Pure Counter
   */
  new PureCounter();

  /**
   * Correct scrolling position upon page load for URLs containing hash links.
   */
  window.addEventListener('load', function(e) {
    if (window.location.hash) {
      if (document.querySelector(window.location.hash)) {
        setTimeout(() => {
          let section = document.querySelector(window.location.hash);
          let scrollMarginTop = getComputedStyle(section).scrollMarginTop;
          window.scrollTo({
            top: section.offsetTop - parseInt(scrollMarginTop),
            behavior: 'smooth'
          });
        }, 100);
      }
    }
  });

  /**
   * Navmenu Scrollspy
   */
  let navmenulinks = document.querySelectorAll('.navmenu a');

  function navmenuScrollspy() {
    navmenulinks.forEach(navmenulink => {
      if (!navmenulink.hash) return;
      let section = document.querySelector(navmenulink.hash);
      if (!section) return;
      let position = window.scrollY + 200;
      if (position >= section.offsetTop && position <= (section.offsetTop + section.offsetHeight)) {
        document.querySelectorAll('.navmenu a.active').forEach(link => link.classList.remove('active'));
        navmenulink.classList.add('active');
      } else {
        navmenulink.classList.remove('active');
      }
    })
  }
  window.addEventListener('load', navmenuScrollspy);
  document.addEventListener('scroll', navmenuScrollspy);

})();


// Scroll to Login/Register Section when Login Button is clicked
document.getElementById('home-login-btn')?.addEventListener('click', () => {
  document.getElementById('login-register').scrollIntoView({ behavior: 'smooth' });
});

// Switch to Register Tab when "Don't have an account? Register" is clicked
document.getElementById('go-to-register')?.addEventListener('click', (e) => {
  e.preventDefault();
  document.querySelectorAll('.tab-link, .tab-content').forEach(item => {
    item.classList.remove('active');
  });
  document.querySelector('[data-tab="register"]').classList.add('active');
  document.getElementById('register').classList.add('active');
});

// Tab Switching
document.querySelectorAll('.tab-link').forEach(tab => {
  tab.addEventListener('click', () => {
    // Remove active class from all tabs and content
    document.querySelectorAll('.tab-link, .tab-content').forEach(item => {
      item.classList.remove('active');
    });

    // Add active class to the clicked tab and corresponding content
    tab.classList.add('active');
    document.getElementById(tab.getAttribute('data-tab')).classList.add('active');
  });
});

// Form Submission Handling
document.getElementById('loginForm')?.addEventListener('submit', function (e) {
  e.preventDefault();
  const email = document.getElementById('login-email').value;
  const password = document.getElementById('login-password').value;
  console.log('Login Data:', { email, password });
  alert('Login successful!');
});

document.getElementById('registerForm')?.addEventListener('submit', function (e) {
  e.preventDefault();
  const username = document.getElementById('register-username').value;
  const email = document.getElementById('register-email').value;
  const password = document.getElementById('register-password').value;
  const confirmPassword = document.getElementById('register-confirm-password').value;

  if (password !== confirmPassword) {
    alert('Passwords do not match!');
    return;
  }

  console.log('Register Data:', { username, email, password });
  alert('Registration successful!');
});



//shop
//proba
const mockCars = [
  {
      id: 1,
      make: "Toyota",
      model: "Camry",
      year: 2022,
      price: 24999,
      mileage: 15000,
      image: "https://via.placeholder.com/300x200?text=Toyota+Camry",
      transmission: "Automatic"
  },
  {
      id: 2, 
      make: "Ford",
      model: "Mustang",
      year: 2021,
      price: 34999,
      mileage: 8000,
      image: "https://via.placeholder.com/300x200?text=Ford+Mustang",
      transmission: "Automatic"
  },
 
];

function renderCars(cars) {
  const container = document.getElementById('carsContainer');
  container.innerHTML = cars.map(car => `
      <div class="car-card" data-id="${car.id}">
          <img src="${car.image}" alt="${car.make} ${car.model}" class="car-image">
          <div class="car-info">
              <h3>${car.year} ${car.make} ${car.model}</h3>
              <div style="display: flex; justify-content: space-between;">
                  <span>${car.mileage.toLocaleString()} mi</span>
                  <span>${car.transmission}</span>
              </div>
              <div class="car-price">$${car.price.toLocaleString()}</div>
              <button class="view-details" data-id="${car.id}">View Details</button>
          </div>
      </div>
  `).join('');
}

function filterCars() {
  const searchTerm = document.getElementById('searchInput').value.toLowerCase();
  const priceRange = document.getElementById('priceFilter').value;
  
  let filtered = mockCars.filter(car => {
      const matchesSearch = `${car.make} ${car.model}`.toLowerCase().includes(searchTerm);
      let matchesPrice = true;
      
      if (priceRange) {
          const [min, max] = priceRange.split('-').map(Number);
          matchesPrice = car.price >= min && (!max || car.price <= max);
      }
      
      return matchesSearch && matchesPrice;
  });
  
  renderCars(filtered);
}


document.getElementById('searchInput').addEventListener('input', filterCars);
document.getElementById('priceFilter').addEventListener('change', filterCars);


renderCars(mockCars);

export function initShopSection() {
  console.log("Shop section initialized");
  renderCars(mockCars);
}