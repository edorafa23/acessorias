<div class="container-fluid mt-5">
    <div class="row justify-content-center">

        <div class="d-flex flex-row flex-wrap justify-content-center">

            <!-- clientes -->
            <?php if ($user->profile == 'agent') : ?>
                <a href="?ct=agent&mt=my_clients" class="unlink m-2">
                    <div class="home-option p-5 text-center">
                        <h3 class="mb-3"><i class="fa-solid fa-users"></i></h3>
                        <h5>Clientes</h5>
                    </div>
                </a>
            <?php endif; ?>

            <!-- adicionar clientes -->
            <?php if ($user->profile == 'agent') : ?>
                <a href="?ct=agent&mt=new_client_form" class="unlink m-2">
                    <div class="home-option p-5 text-center">
                        <h3 class="mb-3"><i class="fa-solid fa-user-plus"></i></h3>
                        <h5>Adicionar clientes</h5>
                    </div>
                </a>
            <?php endif; ?>

            <!-- carregar arquivo de clientes -->
            <?php if ($user->profile == 'agent') : ?>
                <a href="#" class="unlink m-2" data-bs-toggle="modal" data-bs-target="#alertModal">
                    <div class="home-option p-5 text-center">
                        <h3 class="mb-3"><i class="fa-solid fa-upload"></i></h3>
                        <h5>Carregar arquivo</h5>
                    </div>
                </a>
            <?php endif; ?>

            <!-- Modal -->
            <div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="alertModalLabel">Informação</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Funcionalidade ainda não implementada.
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ok</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var myModal = document.getElementById('myModal')
    var myInput = document.getElementById('myInput')

    myModal.addEventListener('shown.bs.modal', function () {
    myInput.focus()
    })
</script>