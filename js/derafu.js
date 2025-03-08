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
                                        Free Software Projects
                </a>
                <div class="dropdown-menu mega-dropdown">
                    <div class="container">
                        <div class="mega-dropdown-header">Free Software Projects</div>
                        <div class="row">
                                                            <div class="col-4 mb-4">
                                    <h6 class="dropdown-header">Base for Applications</h6>
                                                                                                                        <a class="dropdown-item" href="https://derafu.org/foundation">
                                                                                                    <i class="fa-solid fa-house fa-fw me-1"></i>
                                                                                                Derafu Foundation
                                            </a>
                                                                                                                                                                <a class="dropdown-item" href="https://derafu.org/http">
                                                                                                    <i class="fa-solid fa-sitemap fa-fw me-1"></i>
                                                                                                HTTP Flow with Middlewares
                                            </a>
                                                                                                                                                                <a class="dropdown-item" href="https://derafu.org/routing">
                                                                                                    <i class="fa-solid fa-arrows-split-up-and-left fa-fw me-1"></i>
                                                                                                Routing with Parsers
                                            </a>
                                                                                                                                                                <a class="dropdown-item" href="https://derafu.org/kernel">
                                                                                                    <i class="fa-solid fa-heart fa-fw me-1"></i>
                                                                                                Core with MicroKernel
                                            </a>
                                                                                                                                                                <a class="dropdown-item" href="https://derafu.org/backbone">
                                                                                                    <i class="fa-solid fa-bone fa-fw me-1"></i>
                                                                                                Backbone for Libraries
                                            </a>
                                                                                                                                                                <a class="dropdown-item" href="https://derafu.org/translation">
                                                                                                    <i class="fa-solid fa-earth-americas fa-fw me-1"></i>
                                                                                                Translation with I18n/ICU
                                            </a>
                                                                                                                                                                <a class="dropdown-item" href="https://derafu.org/log">
                                                                                                    <i class="fa-solid fa-history fa-fw me-1"></i>
                                                                                                Logbook
                                            </a>
                                                                                                                                                                <a class="dropdown-item" href="https://derafu.org/support">
                                                                                                    <i class="fa-solid fa-life-ring fa-fw me-1"></i>
                                                                                                Support with Helpers
                                            </a>
                                                                                                            </div>
                                                            <div class="col-4 mb-4">
                                    <h6 class="dropdown-header">Data Handling</h6>
                                                                                                                        <a class="dropdown-item" href="https://derafu.org/orm">
                                                                                                    <i class="fa-solid fa-table fa-fw me-1"></i>
                                                                                                ORM: Models and Entities
                                            </a>
                                                                                                                                                                <a class="dropdown-item" href="https://derafu.org/query">
                                                                                                    <i class="fa-solid fa-database fa-fw me-1"></i>
                                                                                                SQL Query Operators
                                            </a>
                                                                                                                                                                <a class="dropdown-item" href="https://derafu.org/data-processor">
                                                                                                    <i class="fa-solid fa-list-check fa-fw me-1"></i>
                                                                                                Data Processing
                                            </a>
                                                                                                                                                                <a class="dropdown-item" href="https://derafu.org/repository">
                                                                                                    <i class="fa-solid fa-folder-tree fa-fw me-1"></i>
                                                                                                File Repositories
                                            </a>
                                                                                                                                                                <a class="dropdown-item" href="https://derafu.org/config">
                                                                                                    <i class="fa-solid fa-cogs fa-fw me-1"></i>
                                                                                                Configuration and Options
                                            </a>
                                                                                                                                                                <a class="dropdown-item" href="https://derafu.org/container">
                                                                                                    <i class="fa-solid fa-box-archive fa-fw me-1"></i>
                                                                                                Data Containers
                                            </a>
                                                                                                                                                                <a class="dropdown-item" href="https://derafu.org/selector">
                                                                                                    <i class="fa-solid fa-filter fa-fw me-1"></i>
                                                                                                Array Selection and Filtering
                                            </a>
                                                                                                                                                                <a class="dropdown-item" href="https://derafu.org/xml">
                                                                                                    <i class="fa-solid fa-code fa-fw me-1"></i>
                                                                                                XML Encoding and Validation
                                            </a>
                                                                                                            </div>
                                                            <div class="col-4 mb-4">
                                    <h6 class="dropdown-header">Views and Templates</h6>
                                                                                                                        <a class="dropdown-item" href="https://derafu.org/renderer">
                                                                                                    <i class="fa-solid fa-palette fa-fw me-1"></i>
                                                                                                Unified Renderer
                                            </a>
                                                                                                                                                                <a class="dropdown-item" href="https://derafu.org/twig">
                                                                                                    <i class="fa-solid fa-file-code fa-fw me-1"></i>
                                                                                                UX Components for Twig
                                            </a>
                                                                                                                                                                <a class="dropdown-item" href="https://derafu.org/markdown">
                                                                                                    <i class="fa-solid fa-file-lines fa-fw me-1"></i>
                                                                                                Markdown Templates
                                            </a>
                                                                                                                                                                <a class="dropdown-item" href="https://derafu.org/form">
                                                                                                    <i class="fa-solid fa-keyboard fa-fw me-1"></i>
                                                                                                Forms
                                            </a>
                                                                                                                                                                <a class="dropdown-item" href="https://derafu.org/vanilla-js">
                                                                                                    <i class="fa-brands fa-js fa-fw me-1"></i>
                                                                                                Vanilla JS
                                            </a>
                                                                                                                                                                <a class="dropdown-item" href="https://derafu.org/html">
                                                                                                    <i class="fa-brands fa-html5 fa-fw me-1"></i>
                                                                                                HTML Components
                                            </a>
                                                                                                                                                                <a class="dropdown-item" href="https://derafu.org/chart">
                                                                                                    <i class="fa-solid fa-chart-line fa-fw me-1"></i>
                                                                                                Graphics with GD
                                            </a>
                                                                                                                                                                <a class="dropdown-item" href="https://www.npmjs.com/package/vite-plugin-d2">
                                                                                                    <i class="fa-solid fa-vector-square fa-fw me-1"></i>
                                                                                                D2 diagrams to SVG
                                            </a>
                                                                                                            </div>
                                                            <div class="col-4 mb-4">
                                    <h6 class="dropdown-header">Services for Applications</h6>
                                                                                                                        <a class="dropdown-item" href="https://derafu.org/mail">
                                                                                                    <i class="fa-solid fa-envelope fa-fw me-1"></i>
                                                                                                Email Sending
                                            </a>
                                                                                                                                                                <a class="dropdown-item" href="https://derafu.org/api">
                                                                                                    <i class="fa-solid fa-server fa-fw me-1"></i>
                                                                                                API with Discovery
                                            </a>
                                                                                                            </div>
                                                            <div class="col-4 mb-4">
                                    <h6 class="dropdown-header">Security and Authentication</h6>
                                                                                                                        <a class="dropdown-item" href="https://derafu.org/certificate">
                                                                                                    <i class="fa-solid fa-certificate fa-fw me-1"></i>
                                                                                                Digital Certificates
                                            </a>
                                                                                                                                                                <a class="dropdown-item" href="https://derafu.org/signature">
                                                                                                    <i class="fa-solid fa-signature fa-fw me-1"></i>
                                                                                                Digital Signature and XML-DSIG
                                            </a>
                                                                                                            </div>
                                                            <div class="col-4 mb-4">
                                    <h6 class="dropdown-header">Standards and Rules</h6>
                                                                                                                        <a class="dropdown-item" href="https://packagist.org/packages/derafu/l10n-cl-rut">
                                                                                                    <i class="fa-solid fa-id-badge fa-fw me-1"></i>
                                                                                                L10n: Chile - RUN/RUT Formatting and Validation
                                            </a>
                                                                                                                                                                <a class="dropdown-item" href="https://packagist.org/packages/derafu/l10n-cl-enum">
                                                                                                    <i class="fa-solid fa-list fa-fw me-1"></i>
                                                                                                L10n: Chile - Enum Collection
                                            </a>
                                                                                                                                                                <a class="dropdown-item" href="https://packagist.org/packages/derafu/enum">
                                                                                                    <i class="fa-solid fa-globe fa-fw me-1"></i>
                                                                                                Global Enum Collection
                                            </a>
                                                                                                            </div>
                                                    </div>
                                                    <div class="mega-dropdown-footer">
                                                                                                            <a href="/free-software" class="mega-dropdown-footer-item">
                                                                                            <i class="fa-solid fa-box-open me-1"></i>
                                                                                        More About Free Software
                                        </a>
                                                                                                                                                <a href="/docs" class="mega-dropdown-footer-item">
                                                                                            <i class="fa-solid fa-book me-1"></i>
                                                                                        Documentation
                                        </a>
                                                                                                                                                <a href="https://github.com/derafu" class="mega-dropdown-footer-item">
                                                                                            <i class="fa-brands fa-github me-1"></i>
                                                                                        See on Github
                                        </a>
                                                                                                </div>
                                            </div>
                </div>
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
                                        Education
                </a>
            </li>
                                <li class="nav-item">
                <a class="nav-link" href="https://derafu.org/#community">
                                            <i class="fa-solid fa-handshake-angle me-1"></i>
                                        Community
                </a>
            </li>
                                <li class="nav-item">
                <a class="nav-link" href="https://derafu.org/#team">
                                            <i class="fa-solid fa-people-group me-1"></i>
                                        Team
                </a>
            </li>
            

                                                    <li class="nav-item ms-2">
                        <a href="#contact" class="btn btn-primary">
                                                            <i class="fa-solid fa-circle-info me-1"></i>
                                                        Contact &amp; Collaboration
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
                                            <h5 class="derafu-block-footer__title mb-4">Featured Projects</h5>
                                                                                    <ul class="derafu-block-footer__links list-unstyled">
                                                            <li>
                                    <a href="https://derafu.org/twig" >
                                                                                    <i class="fa-solid fa-file-code me-2"></i>
                                                                                UX Components for Twig
                                    </a>
                                </li>
                                                            <li>
                                    <a href="https://derafu.org/query" >
                                                                                    <i class="fa-solid fa-database me-2"></i>
                                                                                SQL Query Operators
                                    </a>
                                </li>
                                                            <li>
                                    <a href="https://derafu.org/data-processor" >
                                                                                    <i class="fa-solid fa-list-check me-2"></i>
                                                                                Data Processing
                                    </a>
                                </li>
                                                            <li>
                                    <a href="https://derafu.org/selector" >
                                                                                    <i class="fa-solid fa-filter me-2"></i>
                                                                                Array Selection and Filtering
                                    </a>
                                </li>
                                                    </ul>
                                                        </div>
            
                        
                                        <div class="col">
                                                                <h5 class="derafu-block-footer__title mb-4">Contact &amp; Collaboration</h5>
                                                                <div class="derafu-block-footer__text mb-4">If you are interested in contributing, collaborating, services or anything else, contact us.</div>
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
                                                                <div class="derafu-block-footer__text mb-4"><p>Santa Cruz, Colchagua Valley, Chile.</p><p>From Chile for the world with <i class='fa-solid fa-heart text-danger'></i></p></div>
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
                                                    <div class="derafu-block-footer__bottom-text">Derafu ORG is part of Derafu CORP</div>
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
