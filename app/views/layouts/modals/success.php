<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom-0 justify-content-center">
                <span><i class="fa-regular fa-circle-check fa-3x custom-success"></i></span>
            </div>
            <div class=" modal-body pb-4 text-center fs-5">
                Utilisateur enregistré avec succès !
            </div>
            <div class="text-center mb-3">
                <button type="button" class="btn btn-success btn-sm" data-bs-dismiss="modal" aria-label="Close">
                    OK
                </button>
            </div>

        </div>
    </div>
</div>
<script>
    const modalEl = document.getElementById("exampleModal");
    modalEl.addEventListener("shown.bs.modal", () => {
        setTimeout(() => {
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();
        }, 1000);
    });
</script>