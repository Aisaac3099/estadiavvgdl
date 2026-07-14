<!-- Isaac Gonzalez -->
<?= $this->include('layouts/headerListas') ?>
<?= $this->include('layouts/preloader') ?>
<?= $this->include('layouts/navbar') ?>
<?= $this->include('layouts/sidebar') ?>  

<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <h1>Programar Servicio</h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            <div class="card">

                <div class="card-header">
                    <h3 class="card-title">
                        Servicio por asignar
                    </h3>
                </div>

                <form method="POST" action="<?= base_url('servicios_autos/store') ?>">
                    <input type="hidden" name="origen" value="programar">
                    <div class="card-body">

                        <input type="hidden" name="tipo_registro" value="por_asignar">

                        <div class="form-group">
                            <label>Auto</label>
                            <input
                                type="text"
                                class="form-control"
                                value="<?= esc($auto['alias']) ?>"
                                readonly>

                            <input
                                type="hidden"
                                name="auto_id"
                                value="<?= $auto['id'] ?>">
                                <input type="hidden"
                                name="fecha_servicio"
                                value="<?= date('Y-m-d')?>">
                        </div>

                        <div class="form-group">
                            <label>Tipo Servicio</label>
                            <input
                                type="text"
                                name="tipo_servicio"
                                class="form-control"
                                placeholder="Ej. Cambio de aceite"
                                required>
                        </div>

                        <div class="form-group">
                            <label>Fecha Programada</label>
                            <input
                                type="date"
                                name="proximo_servicio"
                                class="form-control">
                        </div>

                    </div>

                    <div class="card-footer">

                        <button type="submit" class="btn btn-warning">
                            Programar Servicio
                        </button>

                        <a href="<?= base_url('autos') ?>" class="btn btn-secondary">
                            Cancelar
                        </a>

                    </div>

                </form>

            </div>

        </div>
    </section>

</div>

<?= $this->include('layouts/footerListas') ?>