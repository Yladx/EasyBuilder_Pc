const toggleSidebar = document.getElementById('toggleSidebar');
const body = document.body;
const icon = toggleSidebar.querySelector('i');

// Sidebar toggle functionality
toggleSidebar.addEventListener('click', function () {
    body.classList.toggle('sidebar-collapsed');
    if (body.classList.contains('sidebar-collapsed')) {
        icon.classList.remove('fa-close');
        icon.classList.add('fa-bars');
    } else {
        icon.classList.remove('fa-bars');
        icon.classList.add('fa-close');
    }
});

// Click event on module titles
const moduleTitles = document.querySelectorAll('.module-title');
const mainContent = document.getElementById('mainContent');
const introductionLink = document.getElementById('introductionLink');
const introductionContent = document.querySelector('.learningmodules-content').innerHTML;

moduleTitles.forEach(function (title) {
    title.addEventListener('click', function (e) {
        e.preventDefault();
        const moduleId = this.getAttribute('data-id');
        // Update URL without reloading the page
        window.history.pushState({}, '', `/learning-modules/${moduleId}`);
        loadModuleContent(moduleId);
    });
});
