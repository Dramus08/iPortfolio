function showToast(type, message, time = 10000) {
            // Créer ou récupérer le conteneur de toast
            const toastContainer = document.getElementById('toastContainer') || createToastContainer();
            
            // Créer un nouveau toast à chaque fois plutôt que de réutiliser le même
            const toastId = 'toast-' + Date.now();
            const toast = document.createElement('div');
            toast.id = toastId;
            toast.className = 'toast fade show';
            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', 'assertive');
            toast.setAttribute('aria-atomic', 'true');
            
            // Déterminer l'icône et le titre en fonction du type
            const typeConfig = {
                success: { icon: 'check-circle', title: 'Succès' },
                danger: { icon: 'exclamation-triangle', title: 'Erreur' },
                error: { icon: 'exclamation-triangle', title: 'Erreur' },
                warning: { icon: 'exclamation-circle', title: 'Avertissement' },
                info: { icon: 'info-circle', title: 'Information' }
            };
            
            const config = typeConfig[type] || typeConfig.info;
            
            // Construire le contenu du toast
            toast.innerHTML = `
                <div class="toast-header">
                    <strong class="me-auto text-${type}"><i class="bi bi-${config.icon} mx-1"></i>${config.title}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body bg-${type} text-white rounded p-2 mb-2 shadow">
                    ${message}
                </div>
            `;
            
            // Ajouter le toast au conteneur
            toastContainer.appendChild(toast);
            
            // Gérer la fermeture automatique
            const closeToast = () => {
                toast.classList.remove('show');
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                }, 300);
            };
            
            // Fermeture automatique après le délai
            const timeoutId = setTimeout(closeToast, time);
            
            // Gérer la fermeture manuelle
            const closeButton = toast.querySelector('.btn-close');
            if (closeButton) {
                closeButton.addEventListener('click', () => {
                    clearTimeout(timeoutId);
                    closeToast();
                });
            }
            
            // Activer le toast avec Bootstrap si disponible
            if (typeof bootstrap !== 'undefined' && bootstrap.Toast) {
                new bootstrap.Toast(toast).show();
            }
    }

    function createToastContainer() {
        const container = document.createElement('div');
        container.id = 'toastContainer';
        container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
        container.style.zIndex = '1100';
        document.body.appendChild(container);
        return container;
    }

