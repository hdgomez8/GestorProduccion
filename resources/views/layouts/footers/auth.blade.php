<footer class="footer">
    <div class="container-fluid">
        <nav class="float-left">
            <ul>
                <li>
                    <!-- <a href="#">
                        {{ __('Directorio Telefonico') }}
                    </a> -->
                </li>
                {{-- <li>
            <a href="https://creative-tim.com/presentation">
                {{ __('About Us') }}
            </a>
        </li>
        <li>
            <a href="http://blog.creative-tim.com">
                {{ __('Blog') }}
            </a>
        </li>
        <li>
            <a href="https://www.creative-tim.com/license">
                {{ __('Licenses') }}
            </a>
        </li> --}}
            </ul>
        </nav>
        <div class="copyright float-right">
            &copy;
            <script>
                document.write(new Date().getFullYear())
            </script>, Departamento De TI <i class="material-icons">computer</i> Creciendo para SERVIR a
            nuestra
            GENTE
        </div>
    </div>
<script>
    // Seleccionar el botón y los divs
    const button = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('mySidebar');
    const content = document.querySelector('.main-panel');

    // Agregar un evento clic al botón
    button.addEventListener('click', function() {
        // Si el sidebar está oculto, mostrarlo y reducir el ancho del contenido
        if (sidebar.style.display === 'none') {
            sidebar.style.display = 'block';
            content.style.width = 'calc(100% - ' + sidebar.offsetWidth + 'px)';
            // Si el sidebar está visible, ocultarlo y volver al ancho anterior del contenido
        } else {
            sidebar.style.display = 'none';
            content.style.width = '100%';
        }
    });
</script>
</footer>
