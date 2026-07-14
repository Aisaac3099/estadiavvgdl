<!-- Isaac Gonzalez -->
<?= $this->include('layouts/headerListas') ?>
<?= $this->include('layouts/preloader') ?>
<?= $this->include('layouts/navbar') ?>
<?= $this->include('layouts/sidebar') ?>

<div class="content-wrapper">

<section class="content-header">

    <div class="container-fluid">

        <h1>Editar Auto</h1>

    </div>

</section>

<section class="content">

    <div class="container-fluid">

        <div class="card">

            <div class="card-header">

                <h3 class="card-title">

                    Datos del Vehículo

                </h3>

            </div>

            <form
                method="POST"
                action="<?= base_url('autos/update/'.$auto['id']) ?>"
                enctype="multipart/form-data">

                <div class="card-body">

                    <div class="form-group">

                        <label>Alias</label>

                        <input
                            type="text"
                            name="alias"
                            class="form-control"
                            value="<?= esc($auto['alias']) ?>"
                            required>

                    </div>

                    <div class="form-group">

                        <label>Placas</label>

                        <input
                            type="text"
                            name="placas"
                            class="form-control"
                            value="<?= esc($auto['placas']) ?>"
                            required>

                    </div>

                    <div class="form-group">

                        <label>Marca</label>

                        <input
                            type="text"
                            name="marca"
                            class="form-control"
                            value="<?= esc($auto['marca']) ?>"
                            required>

                    </div>

                    <div class="form-group">

                        <label>Modelo</label>

                        <input
                            type="text"
                            name="modelo"
                            class="form-control"
                            value="<?= esc($auto['modelo']) ?>"
                            required>

                    </div>

                    <div class="row">
                        <?php foreach($imagenes as $imagen):?>
                            <div class="col-md-3 text center">
                                <img src="<?=base_url('public/uploads/autos/'.$imagen['imagen'])?>" width="200" class="img-thumbnail mr-1 mb-1">
                                <a href="<?= base_url('autos/eliminar-imagen/'.$imagen['id'])?>"
                                class="btn btn-danger brn-sm">Eliminar</a>
                            </div>
                            <?php endforeach; ?>
                    </div>

                    <div class="form-group">
                        <label>Nuevas Imagenes</label>
                        <input type="file"
                        name="imagenes[]"
                        multiple accept="image/*"
                        class="form-control-file"
                        >
                    </div>

                </div>

                <div class="card-footer">

                    <button
                        type="submit"
                        class="btn btn-primary">

                        Guardar Cambios

                    </button>

                    <a
                        href="<?= base_url('autos') ?>"
                        class="btn btn-secondary">

                        Cancelar

                    </a>

                </div>

            </form>

        </div>

    </div>

</section>

</div>

<?= $this->include('layouts/footerListas') ?>