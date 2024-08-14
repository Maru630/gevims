document.addEventListener('DOMContentLoaded', function () {
    AOS.init({
        duration: 1000,
        easing: 'ease-in-out',
        once: true,
        mirror: false
    });

    const scrollLinks = document.querySelectorAll('a.nav-link');

    scrollLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 70,
                    behavior: 'smooth'
                });
            }
        });
    });

    Fancybox.bind('[data-fancybox="gallery"]', {
        Thumbs: false,
        Toolbar: {
            display: [
                { id: 'counter', position: 'center' },
                'close'
            ]
        },
        Image: {
            zoom: false,
            click: false,
            wheel: "slide",
        },
    });
});
