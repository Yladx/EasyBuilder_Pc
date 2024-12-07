class BuildUtils {
    static formatPrice(price) {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD'
        }).format(price);
    }

    static formatTDP(tdp) {
        return `${tdp} W`;
    }

    static validateBuildName(name) {
        return name.trim().length > 0;
    }

    static createComponentElement(component) {
        const div = document.createElement('div');
        div.className = 'selected-component mb-2';
        div.innerHTML = `
            <img src="${component.imgSrc}" alt="${component.name}" class="component-thumbnail">
            <span class="ms-2">${component.name}</span>
            ${component.price ? `<span class="ms-2">${this.formatPrice(component.price)}</span>` : ''}
            ${component.tdp ? `<span class="ms-2 text-muted">${this.formatTDP(component.tdp)}</span>` : ''}
        `;
        return div;
    }

    static showNotification(title, message, type = 'info') {
        Swal.fire({
            title: title,
            text: message,
            icon: type
        });
    }
}

// Export for use in other modules
window.BuildUtils = BuildUtils;
