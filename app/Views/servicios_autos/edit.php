<!-- Isaac Gonzalez -->
<?= $this->include('layouts/headerListas') ?>
<?= $this->include('layouts/preloader') ?>
<?= $this->include('layouts/navbar') ?>
<?= $this->include('layouts/sidebar') ?>

<div class="content-wrapper">

<section class="content-header">

    <div class="container-fluid">

        <h1>Editar Servicio de Auto</h1>

    </div>

</section>

<section class="content">

    <div class="container-fluid">

        <div class="card">

            <div class="card-header">

                <h3 class="card-title">

                    Datos del Servicio del Auto

                </h3>

            </div>

            <?php if($esPendiente):?>
                <form method="POST"
                action="<?=base_url('servicios_autos/update/'.$servicio['id'])?>" enctype="multipart/form-data">
            <div class="card-body">
                <div class="alert alert-warning">
                    Este Servicio está Pendiente. Completa los datos para marcarlo como relizado.
                </div>
                <div class="form-group">
                    <label>Tipo de registro</label>
                    <select name="tipo_registro" id="tipo_registro_realizar"class="form-control" required>
                        <option value="">Seleccione...</option>
                        <option value="unico">Unico</option>
                        <option value="periodico">Periodico</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Fecha Servicio</label>
                    <input type="date"
                    name="fecha_servicio"
                    class="form-control"
                    value="<?=date('Y-m-d')?>" required>
                </div>
                <div class="form-group">
                    <label>Tipo Servicio</label>
                    <input type="text"
                    name="tipo_servicio"
                    class="form-control"
                    value="<?=esc($servicio['tipo_servicio'])?>">
                </div>
                <div class="form-group">
                    <label>Descripcion</label>
                    <input type="text"
                    name="descripcion"
                    class="form-control"
                    value="<?=esc($servicio['descripcion'])?>">
                </div>
                <div class="form-group" id="grupoProximoRealizar">
                    <label>Proximo Servicio</label>
                    <input type="date"
                    name="proximo_servicio"
                    id="proximo_servicio_realizar"
                    class="form-control">
                    <small class="text-muted">
                        Llena este campo si crees necesario programar un proximo servicio.
                    </small>
                    </div>
                    <div class="form-group">
                        <label>Evidencia(s)</label>
                        <input type="file" name="evidencias[]" multiple accept="image/*" class= "form-control">
                    </div>
            </div>
            <div class="card-footer">
                <button type="submit"
                class="btn btn-success">
                    Realizar Servicio
                </button>
                <a href="<?=base_url('servicios_autos')?>" class="btn btn-secondary">
                    Cancelar
                </a>
            </div>
        </form>
            <?php else:?>
            <form
                method="POST"
                action="<?= base_url('servicios_autos/update/'.$servicio['id']) ?>"enctype="multipart/form-data">

                <div class="card-body">

                <input type="hidden"
                    name="tipo_registro"
                    value="<?=esc($servicio['tipo_registro'])?>">
                                    
                    <div class="form-group">
                            <label>Fecha Servicio</label>
                        <input
                            type="date"
                            name="fecha_servicio"
                            class="form-control"
                            value="<?= esc($servicio['fecha_servicio']) ?>"
                            required>

                    </div>

                    <div class="form-group">

                        <label>Tipo Servicio</label>

                        <input
                            type="text"
                            name="tipo_servicio"
                            class="form-control"
                            value="<?= esc($servicio['tipo_servicio']) ?>"
                            required>

                    </div>

                    <div class="form-group">

                        <label>Descripcion</label>

                        <input
                            type="text"
                            name="descripcion"
                            class="form-control"
                            value="<?= esc($servicio['descripcion']) ?>"
                            required>

                    </div>
                    <?php if ($servicio['tipo_registro'] !='unico'):?>

                    <div class="form-group">

                        <label>Proximo Servicio</label>

                        <input
                            type="date"
                            name="proximo_servicio"
                            class="form-control"
                            value="<?= esc($servicio['proximo_servicio']) ?>">

                    </div>
                    <?php endif;?>
                    <div class="form-group">
                        <label>Evidencias Actuales
                            (<?=count($evidencias)?>)
                        </label>
                        <div class="d-flex flex-wrap">
                            <?php if (!empty($evidencias)):?>
                                <?php foreach ($evidencias as $evidencia):?>
                                    <div class="text-center m-1">
                                        <img src="<?= base_url('public/uploads/servicios_autos/'.$evidencia['archivo'])?>" 
                                        class="img-thumbnail" 
                                        style="width:100px; height:100px; object-fit:cover; cursor:pointer;" 
                                        onclick="verImagen(this)">
                                        <br>
                                        <a href="<?=base_url('servicios_autos/eliminar-evidencia/'. $evidencia['id'])?>"
                                        class="btn btn-danger btn-sm mt-1"
                                        onclick="return confirm('¿Desea eliminar esta evidencia?')">
                                    Eliminar
                                </a>
                                    </div>
                                    <?php endforeach;?>
                                    <?php else:?>
                                        <p>Sin evidencias registradas.</p>
                                        <?php endif;?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Evidencia(s)</label>
                        <input type="file" name="evidencias[]" multiple accept="image/*" class= "form-control">
                    </div>

                </div>
                
                <div class="modal fade" id="modalImagen" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                                <div class="modal-body text-center p-2">
                                    <img id="imgPreview" src="" class="img-fluid">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    function verImagen(elemento){
                        let src = elemento.getAttribute('src');
                        document.getElementById('imgPreview').setAttribute('src',src);
                        $('#modalImagen').modal('show');
                    }
                </script>
                <div class="card-footer">

                    <button
                        type="submit"
                        class="btn btn-primary">

                        Guardar Cambios

                    </button>

                    <a
                        href="<?= base_url('servicios_autos') ?>"
                        class="btn btn-secondary">

                        Cancelar

                    </a>

                </div>

            </form>
        <?php endif;?>
        </div>

    </div>

</section>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function(){
        const tipoRegistro = document.getElementById('tipo_registro_realizar');
        const grupoProximo = document.getElementById('grupoProximoRealizar');
        const proximoServicio = document.getElementById('proximo_servicio_realizar');

        function actualizarProximoRealizar(){
            if (!tipoRegistro || !grupoProximo || !proximoServicio) return;
            
            if (tipoRegistro.value === 'unico'){
                grupoProximo.style.display = 'none';
                proximoServicio.required = false;
                proximoServicio.value = '';
            }else if (tipoRegistro.value === 'periodico'){
                grupoProximo.style.display = 'block';
                proximoServicio.required = false;
        }
    }
actualizarProximoRealizar();
tipoRegistro.addEventListener('change', actualizarProximoRealizar);
});
</script>

<?= $this->include('layouts/footerListas') ?>