searchForm = document.querySelector( '.search-form' );

document.querySelector( '#search-btn' ).onclick = () => {
    searchForm.classList.toggle( 'active' );
};

const loginForm = document.querySelector( '.login-form-container' );

// cuando clickeamos el icono de usuario se abre el form de login
document.querySelector( '#login-btn' ).onclick = () => {
    loginForm.classList.toggle( 'active' );
}

// cuando clickeamos el icono de cerrar se cierra el form de login
document.querySelector( '#close-login-btn' ).onclick = () => {
    loginForm.classList.remove( 'active' );
}

window.onscroll = () => {
    searchForm.classList.remove( 'active' );
    if( window.scrollY > 80 ){
        document.querySelector( '.header-2' ).classList.add( 'active' );
    } else {
        document.querySelector( '.header-2' ).classList.remove( 'active' );
    }
}

window.onload = () => {
    if( window.scrollY > 80 ){
        document.querySelector( '.header-2' ).classList.add( 'active' );
    } else {
        document.querySelector( '.header-2' ).classList.remove( 'active' );
    }
}

// Swiper
var swiper = new Swiper( ".books-slider", {
    loop: true,
    centeredSlides: true,
    autoplay: {
      delay: 9500,
      disableOnInteraction: false
    },
    breakpoints: {
      0: {
        slidesPerView: 1
      },
      768: {
        slidesPerView: 2
      },
      1024: {
        slidesPerView: 3
      },
    },
});

// Swiper
var swiper = new Swiper( ".ingresos-slider", {
  spaceBetween: 10,
  loop: true,
  centeredSlides: true,
  autoplay: {
    delay: 9500,
    disableOnInteraction: false
  },
  breakpoints: {
    0: {
      slidesPerView: 1
    },
    768: {
      slidesPerView: 2
    },
    1024: {
      slidesPerView: 3
    },
  },
});

var swiper = new Swiper( ".reviews-slider", {
  spaceBetween: 10,
  grabCursor: true,
  loop: true,
  centeredSlides: true,
  autoplay: {
    delay: 9500,
    disableOnInteraction: false
  },
  breakpoints: {
    0: {
      slidesPerView: 1
    },
    768: {
      slidesPerView: 2
    },
    1024: {
      slidesPerView: 3
    },
  },
});