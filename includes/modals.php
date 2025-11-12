<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Confirmer la suppression
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer l'utilisateur <strong id="deleteUserName"></strong> ?</p>
                <p class="text-danger"><strong>Cette action est irréversible !</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <a href="#" class="btn btn-danger" id="confirmDelete">Supprimer définitivement</a>
            </div>
        </div>
    </div>
</div>

<!-- Modal de réinitialisation de mot de passe -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">
                    <i class="fas fa-key me-2"></i>
                    Réinitialiser le mot de passe
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir réinitialiser le mot de passe de <strong id="resetPasswordUserName"></strong> ?</p>
                <p>Un nouveau mot de passe sera généré selon le rôle de l'utilisateur.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <a href="#" class="btn btn-warning" id="confirmResetPassword">Réinitialiser</a>
            </div>
        </div>
    </div>
</div>

<!-- Modal d'activation -->
<div class="modal fade" id="activateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-check me-2"></i>
                    Activer l'utilisateur
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir activer le compte de <strong id="activateUserName"></strong> ?</p>
                <p>L'utilisateur pourra à nouveau se connecter au système.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <a href="#" class="btn btn-success" id="confirmActivate">Activer</a>
            </div>
        </div>
    </div>
</div>

<!-- Modal de désactivation -->
<div class="modal fade" id="deactivateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">
                    <i class="fas fa-ban me-2"></i>
                    Désactiver l'utilisateur
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir désactiver le compte de <strong id="deactivateUserName"></strong> ?</p>
                <p>L'utilisateur ne pourra plus se connecter au système.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <a href="#" class="btn btn-warning" id="confirmDeactivate">Désactiver</a>
            </div>
        </div>
    </div>
</div>

<script>
// Gestion des modals
const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
const resetPasswordModal = new bootstrap.Modal(document.getElementById('resetPasswordModal'));
const activateModal = new bootstrap.Modal(document.getElementById('activateModal'));
const deactivateModal = new bootstrap.Modal(document.getElementById('deactivateModal'));

function confirmDelete(userId, userName) {
    document.getElementById('deleteUserName').textContent = userName;
    document.getElementById('confirmDelete').href = '<?= $Router::route('user_delete', ['id' => '']) ?>' + userId;
    deleteModal.show();
}

function confirmResetPassword(userId, userName) {
    document.getElementById('resetPasswordUserName').textContent = userName;
    document.getElementById('confirmResetPassword').href = '<?= $Router::route('user_reset_password', ['id' => '']) ?>' + userId;
    resetPasswordModal.show();
}

function confirmActivate(userId, userName) {
    document.getElementById('activateUserName').textContent = userName;
    document.getElementById('confirmActivate').href = '<?= $Router::route('user_activate', ['id' => '']) ?>' + userId;
    activateModal.show();
}

function confirmDeactivate(userId, userName) {
    document.getElementById('deactivateUserName').textContent = userName;
    document.getElementById('confirmDeactivate').href = '<?= $Router::route('user_deactivate', ['id' => '']) ?>' + userId;
    deactivateModal.show();
}
</script>