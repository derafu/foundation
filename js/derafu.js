function loadDerafuHeader(containerId) {
    document.getElementById(containerId).innerHTML = `
        

<nav class="derafu-block-header navbar navbar-expand-lg  navbar-dark sticky-top bg-dark"
           id="top">
    <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="/">
                                        <span class="ms-2"><span class='fw-bold text-primary'>Derafu</span> <span class='fw-normal text-white'>ORG</span></span>
                    </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>

                <div class="collapse navbar-collapse" id="navbarContent">
                        <ul class="navbar-nav me-auto">
                                        <li class="nav-item dropdown mega-menu-wrapper">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                            <i class="fa-brands fa-github me-1"></i>
                                        Proyectos de Código Abierto
                </a>
                <div class="dropdown-menu mega-dropdown">
                    <div class="container">
                        <div class="mega-dropdown-header">Proyectos de Código Abierto</div>
                        <div class="row">
                                                            <div class="col mb-4">
                                    <h6 class="dropdown-header">Base para Aplicación</h6>
                                                                                                                        <a class="dropdown-item" href="https://derafu.org/foundation">
                                                                                                    <i class="fa-solid fa-house me-1"></i>
                                                                                                Base de Sitios de Derafu
                                            </a>
                                                                                                                                                                <a class="dropdown-item" href="https://derafu.org/http">
                                                                                                    <i class="fa-solid fa-sitemap me-1"></i>
                                                                                                Flujo HTTP con Middlewares
                                            </a>
                                                                                                                                                                <a class="dropdown-item" href="https://derafu.org/routing">
                                                                                                    <i class="fa-solid fa-arrows-split-up-and-left me-1"></i>
                                                                                                Enrutamiento con Parsers
                                            </a>
                                                                                                                                                                <a class="dropdown-item" href="https://derafu.org/kernel">
                                                                                                    <i class="fa-solid fa-heart me-1"></i>
                                                                                                Núcleo con MicroKernel
                                            </a>
                                                                                                                                                                <a class="dropdown-item" href="https://derafu.org/translation">
                                                                                                    <i class="fa-solid fa-earth-americas me-1"></i>
                                                                                                Traducción con I18n/ICU
                                            </a>
                                                                                                                                                                <a class="dropdown-item" href="https://derafu.org/support">
                                                                                                    <i class="fa-solid fa-life-ring me-1"></i>
                                                                                                Apoyo con Helpers
                                            </a>
                                                                                                            </div>
                                                            <div class="col mb-4">
                                    <h6 class="dropdown-header">Manejo de Datos</h6>
                                                                                                                        <a class="dropdown-item" href="https://derafu.org/query">
                                                                                                    <i class="fa-solid fa-database me-1"></i>
                                                                                                Operadores y Generador de Consultas SQL
                                            </a>
                                                                                                                                                                <a class="dropdown-item" href="https://derafu.org/data-processor">
                                                                                                    <i class="fa-solid fa-list-check me-1"></i>
                                                                                                Procesamiento de Datos
                                            </a>
                                                                                                                                                                <a class="dropdown-item" href="https://derafu.org/repository">
                                                                                                    <i class="fa-solid fa-folder-tree me-1"></i>
                                                                                                Repositorios mediante Archivos
                                            </a>
                                                                                                                                                                <a class="dropdown-item" href="https://derafu.org/config">
                                                                                                    <i class="fa-solid fa-cogs me-1"></i>
                                                                                                Configuración y Opciones
                                            </a>
                                                                                                                                                                <a class="dropdown-item" href="https://derafu.org/container">
                                                                                                    <i class="fa-solid fa-box-archive me-1"></i>
                                                                                                Contenedores de Datos
                                            </a>
                                                                                                                                                                <a class="dropdown-item" href="https://derafu.org/selector">
                                                                                                    <i class="fa-solid fa-filter me-1"></i>
                                                                                                Selección y Filtrado en Arreglos
                                            </a>
                                                                                                            </div>
                                                            <div class="col mb-4">
                                    <h6 class="dropdown-header">Vistas y Plantillas</h6>
                                                                                                                        <a class="dropdown-item" href="https://derafu.org/renderer">
                                                                                                    <i class="fa-solid fa-palette me-1"></i>
                                                                                                Renderizador Unificado
                                            </a>
                                                                                                                                                                <a class="dropdown-item" href="https://derafu.org/twig">
                                                                                                    <i class="fa-solid fa-file-code me-1"></i>
                                                                                                Componentes UX para Twig
                                            </a>
                                                                                                                                                                <a class="dropdown-item" href="https://derafu.org/markdown">
                                                                                                    <i class="fa-solid fa-file-lines me-1"></i>
                                                                                                Plantillas con Markdown
                                            </a>
                                                                                                                                                                <a class="dropdown-item" href="https://derafu.org/form">
                                                                                                    <i class="fa-solid fa-keyboard me-1"></i>
                                                                                                Formularios
                                            </a>
                                                                                                                                                                <a class="dropdown-item" href="https://derafu.org/html">
                                                                                                    <i class="fa-solid fa-code me-1"></i>
                                                                                                Componentes HTML
                                            </a>
                                                                                                                                                                <a class="dropdown-item" href="https://derafu.org/chart">
                                                                                                    <i class="fa-solid fa-chart-line me-1"></i>
                                                                                                Gráficos con GD
                                            </a>
                                                                                                            </div>
                                                    </div>
                                                    <div class="mega-dropdown-footer">
                                                                                                            <a href="/docs" class="mega-dropdown-footer-item">
                                                                                            <i class="fa-solid fa-book me-1"></i>
                                                                                        Documentación
                                        </a>
                                                                                                                                                <a href="https://github.com/derafu" class="mega-dropdown-footer-item">
                                                                                            <i class="fa-brands fa-github me-1"></i>
                                                                                        Ver en Github
                                        </a>
                                                                                                </div>
                                            </div>
                </div>
            </li>
                                <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                            <i class="fa-solid fa-book me-1"></i>
                                        Documentación
                </a>
                <ul class="dropdown-menu ">
                                                                        <li class="dropend">
                                <a class="dropdown-item dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                                                            <i class="fa-solid fa-house me-1"></i>
                                                                        Base de Sitios de Derafu
                                </a>
                                <ul class="dropdown-menu submenu">
                                                                                                                        <li>
                                                <a class="dropdown-item" href="https://derafu.org/foundation">
                                                                                                        README
                                                </a>
                                            </li>
                                                                                                            </ul>
                            </li>
                                                                                                <li class="dropend">
                                <a class="dropdown-item dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                                                            <i class="fa-solid fa-sitemap me-1"></i>
                                                                        Flujo HTTP con Middlewares
                                </a>
                                <ul class="dropdown-menu submenu">
                                                                                                                        <li>
                                                <a class="dropdown-item" href="https://derafu.org/http">
                                                                                                        README
                                                </a>
                                            </li>
                                                                                                            </ul>
                            </li>
                                                                                                <li class="dropend">
                                <a class="dropdown-item dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                                                            <i class="fa-solid fa-arrows-split-up-and-left me-1"></i>
                                                                        Enrutamiento con Parsers
                                </a>
                                <ul class="dropdown-menu submenu">
                                                                                                                        <li>
                                                <a class="dropdown-item" href="https://derafu.org/routing">
                                                                                                        README
                                                </a>
                                            </li>
                                                                                                                                                                <li><hr class="dropdown-divider"></li>
                                                                                                                                                                <li>
                                                <a class="dropdown-item" href="https://derafu.org/routing/guide_en.html">
                                                                                                        Guía en Inglés
                                                </a>
                                            </li>
                                                                                                                                                                <li>
                                                <a class="dropdown-item" href="https://derafu.org/routing/guide_es.html">
                                                                                                        Guía en Español
                                                </a>
                                            </li>
                                                                                                            </ul>
                            </li>
                                                                                                <li class="dropend">
                                <a class="dropdown-item dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                                                            <i class="fa-solid fa-heart me-1"></i>
                                                                        Núcleo con MicroKernel
                                </a>
                                <ul class="dropdown-menu submenu">
                                                                                                                        <li>
                                                <a class="dropdown-item" href="https://derafu.org/kernel">
                                                                                                        README
                                                </a>
                                            </li>
                                                                                                            </ul>
                            </li>
                                                                                                <li class="dropend">
                                <a class="dropdown-item dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                                                            <i class="fa-solid fa-earth-americas me-1"></i>
                                                                        Traducción con I18n/ICU
                                </a>
                                <ul class="dropdown-menu submenu">
                                                                                                                        <li>
                                                <a class="dropdown-item" href="https://derafu.org/translation">
                                                                                                        README
                                                </a>
                                            </li>
                                                                                                            </ul>
                            </li>
                                                                                                <li class="dropend">
                                <a class="dropdown-item dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                                                            <i class="fa-solid fa-life-ring me-1"></i>
                                                                        Apoyo con Helpers
                                </a>
                                <ul class="dropdown-menu submenu">
                                                                                                                        <li>
                                                <a class="dropdown-item" href="https://derafu.org/support">
                                                                                                        README
                                                </a>
                                            </li>
                                                                                                            </ul>
                            </li>
                                                                                                <li><hr class="dropdown-divider"></li>
                                                                                                <li class="dropend">
                                <a class="dropdown-item dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                                                            <i class="fa-solid fa-database me-1"></i>
                                                                        Operadores y Generador de Consultas SQL
                                </a>
                                <ul class="dropdown-menu submenu">
                                                                                                                        <li>
                                                <a class="dropdown-item" href="https://derafu.org/query">
                                                                                                        README
                                                </a>
                                            </li>
                                                                                                            </ul>
                            </li>
                                                                                                <li class="dropend">
                                <a class="dropdown-item dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                                                            <i class="fa-solid fa-list-check me-1"></i>
                                                                        Procesamiento de Datos
                                </a>
                                <ul class="dropdown-menu submenu">
                                                                                                                        <li>
                                                <a class="dropdown-item" href="https://derafu.org/data-processor">
                                                                                                        README
                                                </a>
                                            </li>
                                                                                                            </ul>
                            </li>
                                                                                                <li class="dropend">
                                <a class="dropdown-item dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                                                            <i class="fa-solid fa-folder-tree me-1"></i>
                                                                        Repositorios mediante Archivos
                                </a>
                                <ul class="dropdown-menu submenu">
                                                                                                                        <li>
                                                <a class="dropdown-item" href="https://derafu.org/repository">
                                                                                                        README
                                                </a>
                                            </li>
                                                                                                            </ul>
                            </li>
                                                                                                <li class="dropend">
                                <a class="dropdown-item dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                                                            <i class="fa-solid fa-cogs me-1"></i>
                                                                        Configuración y Opciones
                                </a>
                                <ul class="dropdown-menu submenu">
                                                                                                                        <li>
                                                <a class="dropdown-item" href="https://derafu.org/config">
                                                                                                        README
                                                </a>
                                            </li>
                                                                                                            </ul>
                            </li>
                                                                                                <li class="dropend">
                                <a class="dropdown-item dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                                                            <i class="fa-solid fa-box-archive me-1"></i>
                                                                        Contenedores de Datos
                                </a>
                                <ul class="dropdown-menu submenu">
                                                                                                                        <li>
                                                <a class="dropdown-item" href="https://derafu.org/container">
                                                                                                        README
                                                </a>
                                            </li>
                                                                                                            </ul>
                            </li>
                                                                                                <li class="dropend">
                                <a class="dropdown-item dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                                                            <i class="fa-solid fa-filter me-1"></i>
                                                                        Selección y Filtrado en Arreglos
                                </a>
                                <ul class="dropdown-menu submenu">
                                                                                                                        <li>
                                                <a class="dropdown-item" href="https://derafu.org/selector">
                                                                                                        README
                                                </a>
                                            </li>
                                                                                                            </ul>
                            </li>
                                                                                                <li><hr class="dropdown-divider"></li>
                                                                                                <li class="dropend">
                                <a class="dropdown-item dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                                                            <i class="fa-solid fa-palette me-1"></i>
                                                                        Renderizador Unificado
                                </a>
                                <ul class="dropdown-menu submenu">
                                                                                                                        <li>
                                                <a class="dropdown-item" href="https://derafu.org/renderer">
                                                                                                        README
                                                </a>
                                            </li>
                                                                                                            </ul>
                            </li>
                                                                                                <li class="dropend">
                                <a class="dropdown-item dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                                                            <i class="fa-solid fa-file-code me-1"></i>
                                                                        Plantillas con Twig
                                </a>
                                <ul class="dropdown-menu submenu">
                                                                                                                        <li>
                                                <a class="dropdown-item" href="https://derafu.org/twig">
                                                                                                        README
                                                </a>
                                            </li>
                                                                                                                                                                <li>
                                                <a class="dropdown-item" href="https://derafu.org/twig/demo/themes/predefined.html">
                                                                                                        Temas Predefinidos
                                                </a>
                                            </li>
                                                                                                                                                                <li>
                                                <a class="dropdown-item" href="https://derafu.org/twig/components.html">
                                                                                                        Componentes
                                                </a>
                                            </li>
                                                                                                            </ul>
                            </li>
                                                                                                <li class="dropend">
                                <a class="dropdown-item dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                                                            <i class="fa-solid fa-file-lines me-1"></i>
                                                                        Plantillas con Markdown
                                </a>
                                <ul class="dropdown-menu submenu">
                                                                                                                        <li>
                                                <a class="dropdown-item" href="https://derafu.org/markdown">
                                                                                                        README
                                                </a>
                                            </li>
                                                                                                            </ul>
                            </li>
                                                                                                <li class="dropend">
                                <a class="dropdown-item dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                                                            <i class="fa-solid fa-keyboard me-1"></i>
                                                                        Formularios
                                </a>
                                <ul class="dropdown-menu submenu">
                                                                                                                        <li>
                                                <a class="dropdown-item" href="https://derafu.org/form">
                                                                                                        README
                                                </a>
                                            </li>
                                                                                                            </ul>
                            </li>
                                                                                                <li class="dropend">
                                <a class="dropdown-item dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                                                            <i class="fa-solid fa-code me-1"></i>
                                                                        Componentes HTML
                                </a>
                                <ul class="dropdown-menu submenu">
                                                                                                                        <li>
                                                <a class="dropdown-item" href="https://derafu.org/html">
                                                                                                        README
                                                </a>
                                            </li>
                                                                                                            </ul>
                            </li>
                                                                                                <li class="dropend">
                                <a class="dropdown-item dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                                                            <i class="fa-solid fa-chart-line me-1"></i>
                                                                        Gráficos con GD
                                </a>
                                <ul class="dropdown-menu submenu">
                                                                                                                        <li>
                                                <a class="dropdown-item" href="https://derafu.org/chart">
                                                                                                        README
                                                </a>
                                            </li>
                                                                                                            </ul>
                            </li>
                                                            </ul>
            </li>
            
            </ul>

                        <ul class="navbar-nav">
                                        <li class="nav-item">
                <a class="nav-link" href="https://derafu.org/#software">
                                            <i class="fa-solid fa-code me-1"></i>
                                        Software
                </a>
            </li>
                                <li class="nav-item">
                <a class="nav-link" href="https://derafu.org/#education">
                                            <i class="fa-solid fa-user-graduate me-1"></i>
                                        Educación
                </a>
            </li>
                                <li class="nav-item">
                <a class="nav-link" href="https://derafu.org/#community">
                                            <i class="fa-solid fa-handshake-angle me-1"></i>
                                        Comunidad
                </a>
            </li>
                                <li class="nav-item">
                <a class="nav-link" href="https://derafu.org/#team">
                                            <i class="fa-solid fa-people-group me-1"></i>
                                        Equipo
                </a>
            </li>
            

                                                    <li class="nav-item ms-2">
                        <a href="#contact" class="btn btn-primary">
                                                            <i class="fa-solid fa-circle-info me-1"></i>
                                                        Contacto &amp; Colaboración
                        </a>
                    </li>
                            </ul>
        </div>
    </div>
</nav>

    `;
}

function loadDerafuFooter(containerId) {
    document.getElementById(containerId).innerHTML = `
        <footer class="derafu-block-footer " id="contact">
    <div class="container">
        <div class="row">
                                        <div class="col">
                                            <h5 class="derafu-block-footer__title mb-4">Proyectos Destacados</h5>
                                                                                    <ul class="derafu-block-footer__links list-unstyled">
                                                            <li>
                                    <a href="https://derafu.org/twig" >
                                                                                    <i class="fa-solid fa-file-code me-2"></i>
                                                                                Componentes UX para Twig
                                    </a>
                                </li>
                                                            <li>
                                    <a href="https://derafu.org/query" >
                                                                                    <i class="fa-solid fa-database me-2"></i>
                                                                                Operadores y Generador de Consultas SQL
                                    </a>
                                </li>
                                                            <li>
                                    <a href="https://derafu.org/data-processor" >
                                                                                    <i class="fa-solid fa-list-check me-2"></i>
                                                                                Procesamiento de Datos
                                    </a>
                                </li>
                                                            <li>
                                    <a href="https://derafu.org/selector" >
                                                                                    <i class="fa-solid fa-filter me-2"></i>
                                                                                Selección y Filtrado en Arreglos
                                    </a>
                                </li>
                                                    </ul>
                                                        </div>
            
                        
                                        <div class="col">
                                                                <h5 class="derafu-block-footer__title mb-4">Contacto &amp; Colaboración</h5>
                                                                <div class="derafu-block-footer__text mb-4">Si estás interesado en contribuir o colaborar, contáctanos.</div>
                                                                <ul class="derafu-block-footer__links list-unstyled">
                                                            <li>
                                    <a href="" >
                                                                                    <i class="fa-solid fa-envelope derafu-email me-2"></i>
                                                                                
                                    </a>
                                </li>
                                                            <li>
                                    <a href="https://www.derafu.com" >
                                                                                    <i class="fa-solid fa-globe me-2"></i>
                                                                                Derafu CORP
                                    </a>
                                </li>
                                                    </ul>
                                                        </div>
            
                                        <div class="col">
                                                                <h5 class="derafu-block-footer__title mb-4">Derafu ORG</h5>
                                                                <div class="derafu-block-footer__text mb-4"><p>Santa Cruz, Valle de Colchagua, Chile.</p><p>Desde Chile para todo el mundo con <i class='fa-solid fa-heart text-danger'></i></p></div>
                                                                                    <div class="derafu-block-footer__social mt-4">
                                                            <a href="https://github.com/derafu"
                                                                      class="derafu-block-footer__social-icon "
                                   >
                                    <i class="fa-brands fa-github fa-2x "></i>
                                </a>
                                                    </div>
                                    </div>
                    </div>
    </div>

                <div class="derafu-block-footer__bottom">
            <div class="container">
                <div class="row">
                    <div class="col-md">
                                                    <div class="derafu-block-footer__bottom-text">Copyright &copy; 2025 Derafu ORG</div>
                                            </div>
                    <div class="col-md text-md-end">
                                                    <div class="derafu-block-footer__bottom-text">Todos los derechos reservados.</div>
                                            </div>
                </div>
            </div>
        </div>
    </footer>

    `;
}

function encodeToHtmlEntities(text) {
    let encoded = '';
    for (let i = 0; i < text.length; i++) {
        encoded += `&#${text.charCodeAt(i)};`;
    }
    return encoded;
}

function addEmail(className, user, domain) {
    let email = user + "@" + domain;
    let encodedEmail = encodeToHtmlEntities(email);

    const elements = document.getElementsByClassName(className);
    for (let element of elements) {
        element.parentElement.setAttribute("href", "mailto:" + email);
        element.parentElement.innerHTML = encodedEmail;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    loadDerafuHeader('header-container');
    loadDerafuFooter('footer-container');
    addEmail(
        'derafu-email',
        'derafu',
        'derafu.org'
    );
});
