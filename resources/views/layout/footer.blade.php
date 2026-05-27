<!-- ======= Footer ======= -->
<footer id="footer">
    <div class="container">
        <div class="copyright">
            &copy; Copyright <strong><span>Software</span></strong>
        </div>
        <div class="credits">
            <!-- All the links in the footer should remain intact. -->
            <!-- You can delete the links only if you purchased the pro version. -->
            <!-- Licensing information: https://bootstrapmade.com/license/ -->
            <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/iportfolio-bootstrap-portfolio-websites-template/ -->
        </div>
    </div>

</footer><!-- End  Footer -->

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<!-- Vendor JS Files -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{asset('assets/vendor/purecounter/purecounter_vanilla.js')}}"></script>
    <script src="{{asset('assets/vendor/aos/aos.js')}}"></script>
    <script src="{{asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('assets/vendor/glightbox/js/glightbox.min.js')}}"></script>
    <script src="{{asset('assets/vendor/isotope-layout/isotope.pkgd.min.js')}}"></script>
    <script src="{{asset('assets/vendor/swiper/swiper-bundle.min.js')}}"></script>
    <script src="{{asset('assets/vendor/typed.js/typed.js')}}"></script>
    <script src="{{asset('assets/vendor/waypoints/noframework.waypoints.js')}}"></script>
    <script src="{{asset('assets/vendor/php-email-form/validate.js')}}"></script>

    <!-- Template Main JS File -->
    <script src="{{asset('assets/js/main.js')}}"></script>
    <script src="{{ asset('assets/data/restric.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('assets/data/eventos.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('assets/data/valid.js') }}?v={{ time() }}"></script>
    
    <script>
        // Mejora de interacción del menú
        document.addEventListener('DOMContentLoaded', function() {
            // Mantener el estado de los menús colapsados
            const menuStates = {};
            const menuElements = document.querySelectorAll('.nav-link[data-bs-toggle="collapse"]');
            
            menuElements.forEach(element => {
                const target = element.getAttribute('data-bs-target');
                const storedState = localStorage.getItem(target);
                
                if (storedState === 'true') {
                    const bsCollapse = new bootstrap.Collapse(document.querySelector(target));
                    bsCollapse.show();
                    element.classList.remove('collapsed');
                }
                
                element.addEventListener('click', function() {
                    setTimeout(() => {
                        const isCollapsed = document.querySelector(target).classList.contains('show');
                        localStorage.setItem(target, isCollapsed);
                    }, 100);
                });
            });
            
            // Mejorar la interacción en dispositivos móviles
            const mobileNavToggle = document.querySelector('.mobile-nav-toggle');
            if (mobileNavToggle) {
                mobileNavToggle.addEventListener('click', function() {
                    document.body.classList.toggle('mobile-nav-active');
                });
            }
            
            // Cerrar menú al hacer clic en un elemento (en móviles)
            if (window.innerWidth < 1200) {
                const navLinks = document.querySelectorAll('.nav-link');
                navLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        document.body.classList.remove('mobile-nav-active');
                    });
                });
            }
        });
    </script>
</body>
</html>

