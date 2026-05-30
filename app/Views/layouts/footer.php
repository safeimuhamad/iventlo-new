        </div>
        <div class="flex-grow-1"></div>
        <?php if (!empty($_GET['page']) && $_GET['page'] !== 'login'): ?>
            <footer class="footer-area bg-white text-center rounded-10 rounded-bottom-0">
                <p class="fs-16 text-body">
                    © <?= date('Y'); ?>
                    <span class="text-secondary">
                        Iventlo Business Platform
                    </span>
                </p>
            </footer>
        <?php endif; ?>
    </div>
</div>

<script src="<?= asset('js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= asset('js/sidebar-menu.js') ?>"></script>
<script src="<?= asset('js/quill.min.js') ?>"></script>
<script src="<?= asset('js/data-table.js') ?>"></script>
<script src="<?= asset('js/prism.js') ?>"></script>
<script src="<?= asset('js/clipboard.min.js') ?>"></script>
<script src="<?= asset('js/simplebar.min.js') ?>"></script>
<script src="<?= asset('js/apexcharts.min.js') ?>"></script>
<script src="<?= asset('js/echarts.min.js') ?>"></script>
<script src="<?= asset('js/swiper-bundle.min.js') ?>"></script>
<script src="<?= asset('js/fullcalendar.main.js') ?>"></script>
<script src="<?= asset('js/jsvectormap.min.js') ?>"></script>
<script src="<?= asset('js/world-merc.js') ?>"></script>
<script src="<?= asset('js/custom/custom.js') ?>"></script>
<script src="<?= asset('js/tom-select.complete.min.js') ?>"></script>
<script>

function getPageFromUrl(url)
{
    const parsedUrl = new URL(url, window.location.origin);

    let page = parsedUrl.searchParams.get('page');

    if (page) {
        return page;
    }

    const appPath = new URL('<?= addslashes(baseUrl()) ?>').pathname.replace(/\/+$/, '');
    let path = parsedUrl.pathname;

    if (appPath && path.startsWith(appPath)) {
        path = path.slice(appPath.length);
    }

    page = path.replace(/^\/+|\/+$/g, '') || 'dashboard';

    // Bersihkan kalau parameter nyasar pakai &
    page = page.split('&')[0];

    return page || 'dashboard';
}

const postOnlyRoutes = <?= json_encode([
    'units-delete', 'rentals-process-out', 'rentals-process-return', 'rentals-delete-technician',
    'delivery-orders-delete', 'users-delete', 'customers-delete', 'quotations-delete',
    'invoices-delete', 'expenses-delete', 'chart-of-accounts-delete', 'bank-transfers-delete',
    'vendors-delete', 'vendor-bills-delete', 'products-service-delete', 'employees-delete', 'departments-delete',
    'positions-delete', 'attendances-delete', 'leave-requests-approve',
    'leave-requests-delete', 'overtime-requests-approve', 'overtime-requests-delete',
    'payroll-periods-delete', 'payrolls-generate', 'payrolls-paid',
    'marketing-leads-delete', 'recruitment-applicants-delete',
    'recruitment-applicants-convert', 'employee-contracts-delete',
    'purchase-orders-approve', 'purchase-orders-sent', 'purchase-orders-delete',
    'purchase-orders-create-bill', 'goods-receipts-delete', 'purchase-requests-submit-approval',
    'purchase-requests-approve', 'purchase-requests-delete', 'approval-matrices-delete', 'website-sliders-delete',
    'website-inquiries-delete', 'website-services-delete', 'website-posts-delete',
    'website-testimonials-delete', 'website-faqs-delete', 'website-products-delete',
    'website-portfolios-delete', 'rental-orders-create-from-quotation',
    'unit-maintenance-process', 'vehicle-maintenances-process',
    'employee-cash-advances-disburse'
]) ?>;

document.addEventListener('click', function(e) {
    const link = e.target.closest('a[href]');

    if (!link || e.defaultPrevented || !postOnlyRoutes.includes(getPageFromUrl(link.href))) {
        return;
    }

    e.preventDefault();

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = link.href;
    form.innerHTML = '<?= addslashes(csrfField()) ?>';
    document.body.appendChild(form);
    form.submit();
});

function reinitializePageScripts()
{
    if (window.jQuery && $.fn.select2) {
        $('.select2').select2();
    }

    if (window.TomSelect) {
        document.querySelectorAll('.tom-select').forEach(el => {
            if (!el.tomselect) {
                new TomSelect(el);
            }
        });
    }

    if (typeof bootstrap !== 'undefined') {
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
            new bootstrap.Tooltip(el);
        });
    }
}

async function loadSidebarPage(url, pushState = true)
{
    const target = document.getElementById('app-content');

    if (!target) {
        window.location.href = url;
        return;
    }

    const requestedUrl = new URL(url, window.location.origin);
    requestedUrl.searchParams.set('partial', '1');
    target.setAttribute('aria-busy', 'true');
    target.style.opacity = '0.55';

    try {
        const response = await fetch(requestedUrl.toString(), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            credentials: 'same-origin'
        });
        const html = await response.text();

        if (!response.ok || /<!doctype|<html/i.test(html) || response.url.includes('/login')) {
            window.location.href = url;
            return;
        }

        target.innerHTML = html;
        const partialTitle = target.querySelector('template[data-partial-title]');

        if (partialTitle) {
            document.title = partialTitle.dataset.partialTitle;
            partialTitle.remove();
        }

        executePartialPageScripts(target);
        reinitializePageScripts();
        updateActiveMenu(url);

        if (pushState) {
            window.history.pushState({ partialPage: true }, '', url);
        }
    } catch (error) {
        window.location.href = url;
    } finally {
        target.removeAttribute('aria-busy');
        target.style.opacity = '';
    }
}

function executePartialPageScripts(container)
{
    const scripts = Array.from(container.querySelectorAll('script'));

    if (!scripts.length) {
        return;
    }

    const originalAddEventListener = document.addEventListener;

    document.addEventListener = function(type, listener, options) {
        if (type === 'DOMContentLoaded' && typeof listener === 'function') {
            listener.call(document, new Event('DOMContentLoaded'));
            return;
        }

        return originalAddEventListener.call(document, type, listener, options);
    };

    try {
        scripts.forEach(oldScript => {
            const newScript = document.createElement('script');

            Array.from(oldScript.attributes).forEach(attribute => {
                newScript.setAttribute(attribute.name, attribute.value);
            });

            newScript.textContent = oldScript.textContent;
            oldScript.replaceWith(newScript);
        });
    } finally {
        document.addEventListener = originalAddEventListener;
    }
}

function normalizeSidebarPage(page)
{
    if (!page) return 'dashboard';

    page = page
        .split('?')[0]
        .split('&')[0]
        .replace(/^\/+|\/+$/g, '');

    if (page === 'client/dashboard') return 'client-dashboard';
    if (page === 'client/notifications') return 'client-notifications';
    if (page === 'client/events' || page.startsWith('client/events/') || page.startsWith('client/approvals/')) {
        return 'client-events';
    }

    const pageMap = {
        'process-login': 'login',
        'logout': 'login',

        'dashboard-sales': 'dashboard',
        'dashboard-finance': 'dashboard',
        'dashboard-operasional': 'dashboard',
        'dashboard-owner': 'dashboard',

        'units-create': 'units',
        'units-store': 'units',
        'units-edit': 'units',
        'units-update': 'units',
        'units-delete': 'units',

        'partner-units-create': 'partner-units',
        'partner-units-store': 'partner-units',
        'partner-units-edit': 'partner-units',
        'partner-units-update': 'partner-units',

        'rentals-create': 'rentals',
        'rentals-store': 'rentals',
        'rentals-show': 'rentals',
        'rentals-process-out': 'rentals',
        'rentals-process-return': 'rentals',
        'rentals-assign-technician': 'rentals',
        'rentals-store-technician': 'rentals',
        'rentals-delete-technician': 'rentals',
        'rental-items-create': 'rentals',
        'rental-items-store': 'rentals',
        'rental-orders-create-from-quotation': 'rentals',

        'delivery-orders-create': 'delivery-orders',
        'delivery-orders-store': 'delivery-orders',
        'delivery-orders-print': 'delivery-orders',
        'delivery-orders-show': 'delivery-orders',
        'delivery-orders-edit': 'delivery-orders',
        'delivery-orders-update': 'delivery-orders',
        'delivery-orders-delete': 'delivery-orders',

        'technicians-create': 'technicians',
        'technicians-store': 'technicians',
        'technicians-edit': 'technicians',
        'technicians-update': 'technicians',

        'users-create': 'users',
        'users-store': 'users',
        'users-edit': 'users',
        'users-update': 'users',
        'users-delete': 'users',

        'customers-create': 'customers',
        'customers-store': 'customers',
        'customers-edit': 'customers',
        'customers-update': 'customers',
        'customers-delete': 'customers',
        'customers-show': 'customers',
        'customers-store-ajax': 'customers',
        'customers-search-ajax': 'customers',

        'quotations-create': 'quotations',
        'quotations-store': 'quotations',
        'quotations-edit': 'quotations',
        'quotations-update': 'quotations',
        'quotations-show': 'quotations',
        'quotations-print-rental': 'quotations',
        'quotations-print-service': 'quotations',
        'quotations-delete': 'quotations',
        'quotations-create-from-lead': 'quotations',

        'invoices-create': 'invoices',
        'invoices-store': 'invoices',
        'invoices-edit': 'invoices',
        'invoices-update': 'invoices',
        'invoices-show': 'invoices',
        'invoices-print': 'invoices',
        'invoices-delete': 'invoices',
        'invoices-create-from-quotation': 'invoices',
        'invoice-payments-create': 'invoices',
        'invoice-payments-store': 'invoices',

        'bank-transactions': 'bank-accounts',
        'bank-accounts-create': 'bank-accounts',
        'bank-accounts-store': 'bank-accounts',
        'bank-accounts-edit': 'bank-accounts',
        'bank-accounts-update': 'bank-accounts',

        'bank-transfers-create': 'bank-transfers',
        'bank-transfers-store': 'bank-transfers',
        'bank-transfers-show': 'bank-transfers',
        'bank-transfers-delete': 'bank-transfers',

        'expenses-create': 'expenses',
        'expenses-store': 'expenses',
        'expenses-show': 'expenses',
        'expenses-edit': 'expenses',
        'expenses-update': 'expenses',
        'expenses-delete': 'expenses',

        'journal-entries-show': 'journal-entries',

        'chart-of-accounts-create': 'chart-of-accounts',
        'chart-of-accounts-store': 'chart-of-accounts',
        'chart-of-accounts-edit': 'chart-of-accounts',
        'chart-of-accounts-update': 'chart-of-accounts',
        'chart-of-accounts-delete': 'chart-of-accounts',

        'vendors-create': 'vendors',
        'vendors-store': 'vendors',
        'vendors-edit': 'vendors',
        'vendors-update': 'vendors',
        'vendors-delete': 'vendors',

        'vendor-bills-create': 'vendor-bills',
        'vendor-bills-store': 'vendor-bills',
        'vendor-bills-show': 'vendor-bills',
        'vendor-bills-delete': 'vendor-bills',
        'vendor-bill-payments-create': 'vendor-bills',
        'vendor-bill-payments-store': 'vendor-bills',
        'purchase-orders-create': 'purchase-orders',
        'purchase-orders-show': 'purchase-orders',
        'purchase-orders-edit': 'purchase-orders',
        'purchase-orders-update': 'purchase-orders',
        'purchase-orders-approve': 'purchase-orders',
        'purchase-orders-sent': 'purchase-orders',
        'purchase-orders-print': 'purchase-orders',
        'goods-receipts-create': 'goods-receipts',
        'goods-receipts-show': 'goods-receipts',
        'goods-receipts-store': 'goods-receipts',
        'goods-receipts-delete': 'goods-receipts',
        'purchase-orders-create-bill': 'purchase-orders',
        'purchase-requests-create': 'purchase-requests',
        'purchase-requests-store': 'purchase-requests',
        'purchase-requests-show': 'purchase-requests',
        'purchase-requests-edit': 'purchase-requests',
        'purchase-requests-update': 'purchase-requests',
        'purchase-requests-approve': 'purchase-requests',
        'purchase-requests-reject': 'purchase-requests',
        'purchase-requests-delete': 'purchase-requests',
        'purchase-requests-create': 'purchase-requests',
        'purchase-requests-store': 'purchase-requests',
        'purchase-requests-show': 'purchase-requests',
        'purchase-requests-edit': 'purchase-requests',
        'purchase-requests-update': 'purchase-requests',
        'purchase-requests-approve': 'purchase-requests',
        'purchase-requests-reject': 'purchase-requests',
        'purchase-requests-delete': 'purchase-requests',
        'purchase-orders-create-from-pr': 'purchase-orders',
        


        'unit-maintenance-process': 'unit-maintenance',
        'unit-maintenance-store': 'unit-maintenance',
        'unit-maintenance-show': 'unit-maintenance-history',

        'vehicles-create': 'vehicles',
        'vehicles-edit': 'vehicles',
        'vehicles-store': 'vehicles',
        'vehicles-update': 'vehicles',

        'vehicle-usage-logs-create': 'vehicle-usage-logs',
        'vehicle-usage-logs-store': 'vehicle-usage-logs',
        'vehicle-usage-logs-show': 'vehicle-usage-logs',

        'vehicle-maintenances-process': 'vehicle-maintenances',
        'vehicle-maintenances-store': 'vehicle-maintenances',
        'vehicle-maintenances-show': 'vehicle-maintenances-history',

        'products-service-create': 'products-service',
        'products-service-store': 'products-service',
        'products-service-edit': 'products-service',
        'products-service-update': 'products-service',
        'products-service-delete': 'products-service',

        'employees-create': 'employees',
        'employees-store': 'employees',
        'employees-edit': 'employees',
        'employees-update': 'employees',
        'employees-show': 'employees',
        'employees-delete': 'employees',
        'employees-create-user': 'employees',

        'departments-create': 'departments',
        'departments-store': 'departments',
        'departments-edit': 'departments',
        'departments-update': 'departments',
        'departments-delete': 'departments',

        'positions-create': 'positions',
        'positions-store': 'positions',
        'positions-edit': 'positions',
        'positions-update': 'positions',
        'positions-delete': 'positions',

        'attendances-create': 'attendances',
        'attendances-store': 'attendances',
        'attendances-show': 'attendances',
        'attendances-edit': 'attendances',
        'attendances-update': 'attendances',
        'attendances-delete': 'attendances',

        'leave-requests-create': 'leave-requests',
        'leave-requests-store': 'leave-requests',
        'leave-requests-show': 'leave-requests',
        'leave-requests-edit': 'leave-requests',
        'leave-requests-update': 'leave-requests',
        'leave-requests-delete': 'leave-requests',
        'leave-requests-approve': 'leave-requests',
        'leave-requests-reject': 'leave-requests',

        'overtime-requests-create': 'overtime-requests',
        'overtime-requests-store': 'overtime-requests',
        'overtime-requests-show': 'overtime-requests',
        'overtime-requests-edit': 'overtime-requests',
        'overtime-requests-update': 'overtime-requests',
        'overtime-requests-delete': 'overtime-requests',
        'overtime-requests-approve': 'overtime-requests',
        'overtime-requests-reject': 'overtime-requests',

        'payroll-periods-create': 'payroll-periods',
        'payroll-periods-store': 'payroll-periods',
        'payroll-periods-show': 'payroll-periods',
        'payroll-periods-edit': 'payroll-periods',
        'payroll-periods-update': 'payroll-periods',
        'payroll-periods-delete': 'payroll-periods',
        'payrolls': 'payroll-periods',
        'payrolls-generate': 'payroll-periods',
        'payrolls-show': 'payroll-periods',
        'payrolls-print': 'payroll-periods',
        'payrolls-edit': 'payroll-periods',
        'payrolls-update': 'payroll-periods',
        'payrolls-paid': 'payroll-periods',

        'roles-create': 'roles',
        'roles-store': 'roles',
        'roles-edit': 'roles',
        'roles-update': 'roles',
        'roles-permissions': 'roles',
        'roles-permissions-update': 'roles',

        'employee-cash-advances-create': 'employee-cash-advances',
        'employee-cash-advances-store': 'employee-cash-advances',
        'employee-cash-advances-show': 'employee-cash-advances',
        'employee-cash-advances-edit': 'employee-cash-advances',
        'employee-cash-advances-update': 'employee-cash-advances',
        'employee-cash-advances-supervisor-approve': 'employee-cash-advances',
        'employee-cash-advances-finance-approve': 'employee-cash-advances',
        'employee-cash-advances-disburse': 'employee-cash-advances',
        'employee-cash-advances-reject': 'employee-cash-advances',

        'marketing-leads-create': 'marketing-leads',
        'marketing-leads-store': 'marketing-leads',
        'marketing-leads-show': 'marketing-leads',
        'marketing-leads-edit': 'marketing-leads',
        'marketing-leads-update': 'marketing-leads',
        'marketing-leads-delete': 'marketing-leads',
        'marketing-leads-followup-store': 'marketing-leads',
        'marketing-leads-search-ajax': 'marketing-leads',

        'recruitment-applicants-create': 'recruitment-applicants',
        'recruitment-applicants-store': 'recruitment-applicants',
        'recruitment-applicants-show': 'recruitment-applicants',
        'recruitment-applicants-edit': 'recruitment-applicants',
        'recruitment-applicants-update': 'recruitment-applicants',
        'recruitment-applicants-delete': 'recruitment-applicants',
        'recruitment-applicants-convert': 'recruitment-applicants',

        'employee-contracts-create': 'employee-contracts',
        'employee-contracts-store': 'employee-contracts',
        'employee-contracts-show': 'employee-contracts',
        'employee-contracts-edit': 'employee-contracts',
        'employee-contracts-update': 'employee-contracts',
        'employee-contracts-delete': 'employee-contracts',
        'employee-contracts-print': 'employee-contracts',

        'approval-matrices-create': 'approval-matrices',
        'approval-matrices-store': 'approval-matrices',
        'approval-matrices-show': 'approval-matrices',
        'approval-matrices-edit': 'approval-matrices',
        'approval-matrices-update': 'approval-matrices',
        'approval-matrices-delete': 'approval-matrices',
        'approval-requests-show': 'approval-requests',
        'approval-requests-approve': 'approval-requests',
        'approval-requests-reject': 'approval-requests',
        'purchase-requests-submit-approval': 'purchase-requests',

        'activate-account': 'login',
        'activate-account-save': 'login',
        'forgot-password': 'login',
        'forgot-password-send': 'login',
        'reset-password': 'login',
        'reset-password-save': 'login',
        'activity-logs-show': 'activity-logs',
        'website-dashboard': 'website-dashboard',

        'website-settings': 'website-settings',

        'website-sliders-create': 'website-sliders',
        'website-sliders-store': 'website-sliders',
        'website-sliders-edit': 'website-sliders',
        'website-sliders-update': 'website-sliders',
        'website-sliders-delete': 'website-sliders',

        'website-about-edit': 'website-about',
        'website-about-update': 'website-about',

        'website-services-create': 'website-services',
        'website-services-store': 'website-services',
        'website-services-edit': 'website-services',
        'website-services-update': 'website-services',
        'website-services-delete': 'website-services',

        'website-products-create': 'website-products',
        'website-products-store': 'website-products',
        'website-products-edit': 'website-products',
        'website-products-update': 'website-products',
        'website-products-delete': 'website-products',

        'website-posts-create': 'website-posts',
        'website-posts-store': 'website-posts',
        'website-posts-edit': 'website-posts',
        'website-posts-update': 'website-posts',
        'website-posts-delete': 'website-posts',

        'website-portfolios-create': 'website-portfolios',
        'website-portfolios-store': 'website-portfolios',
        'website-portfolios-edit': 'website-portfolios',
        'website-portfolios-update': 'website-portfolios',
        'website-portfolios-delete': 'website-portfolios',

        'website-testimonials-create': 'website-testimonials',
        'website-testimonials-store': 'website-testimonials',
        'website-testimonials-edit': 'website-testimonials',
        'website-testimonials-update': 'website-testimonials',
        'website-testimonials-delete': 'website-testimonials',

        'website-faqs-create': 'website-faqs',
        'website-faqs-store': 'website-faqs',
        'website-faqs-edit': 'website-faqs',
        'website-faqs-update': 'website-faqs',
        'website-faqs-delete': 'website-faqs',

        'website-inquiries-show': 'website-inquiries',
        'website-inquiries-edit': 'website-inquiries',
        'website-inquiries-update': 'website-inquiries'
    };

    return pageMap[page] || page;
}

function updateActiveMenu(url)
{
    const currentPage = normalizeSidebarPage(getPageFromUrl(url));
    console.log('CURRENT PAGE:', currentPage);

    document.querySelectorAll('#layout-menu .menu-item').forEach(item => {
        item.classList.remove('active', 'open');
    });

    document.querySelectorAll('#layout-menu .menu-link').forEach(link => {
        link.classList.remove('active');
    });

    let activeLink = null;

    document.querySelectorAll('#layout-menu a[href]').forEach(link => {
        const href = link.getAttribute('href');

        if (!href || href.includes('javascript:')) return;

        const linkPage = normalizeSidebarPage(getPageFromUrl(href));

        if (linkPage === currentPage) {
            activeLink = link;
        }
    });

    if (!activeLink) return;

    activeLink.classList.add('active');

    const activeItem = activeLink.closest('.menu-item');

    if (activeItem) {
        activeItem.classList.add('active');
    }

    let parentSub = activeLink.closest('.menu-sub');

    while (parentSub) {
        const parentItem = parentSub.closest('.menu-item');

        if (parentItem) {
            parentItem.classList.add('active', 'open');

            const parentToggle = parentItem.querySelector(':scope > .menu-link');
            if (parentToggle) {
                parentToggle.classList.add('active');
            }
        }

        parentSub = parentItem
            ? parentItem.parentElement.closest('.menu-sub')
            : null;
    }
}

document.addEventListener('DOMContentLoaded', function () {
    updateActiveMenu(window.location.href);
    reinitializePageScripts();
});

document.addEventListener('click', function(e) {
    const link = e.target.closest('#layout-menu a[href]');

    if (!link
        || e.defaultPrevented
        || e.button !== 0
        || e.metaKey
        || e.ctrlKey
        || e.shiftKey
        || e.altKey
        || link.target === '_blank'
        || link.hasAttribute('download')
        || link.getAttribute('href').startsWith('javascript:')
        || getPageFromUrl(link.href) === 'logout'
        || postOnlyRoutes.includes(getPageFromUrl(link.href))) {
        return;
    }

    const destination = new URL(link.href, window.location.origin);

    if (destination.origin !== window.location.origin) {
        return;
    }

    e.preventDefault();
    loadSidebarPage(destination.toString());
});

window.addEventListener('popstate', function() {
    loadSidebarPage(window.location.href, false);
});

</script>
</body>
</html>
