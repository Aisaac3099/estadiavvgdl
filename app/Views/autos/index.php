<!-- Isaac Gonzalez -->
<?= $this->include('layouts/headerListas') ?>
<?= $this->include('layouts/preloader') ?>
<?= $this->include('layouts/navbar') ?>
<?= $this->include('layouts/sidebar') ?> 

<div class="content-wrapper">
    <section class ="content-header">
        <div class=container-fluid>
            <div class=row mb-2>
                <div class="col-sm-6">
                    <h1>Autos Registrados</h1>
                </div>
                <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                    <a href="<?=base_url('agenda')?>">
                        Inicio
                    </a>
                    </li>
                
                    <li class= "breadcrumb-item">
                        <a href="<?= base_url('servicios_autos')?>">
                            Servicios de Autos
                        </a>
                    </li>
                    <li class="breadcrumb-item active">
                        Autos
                    </li>
                </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        Lista de Autos  
                    </h3>
                    <div class="float-sm-right">
                        <button class="btn btn-primary btn-sm"
                                        data-toggle="modal"
                                        data-target="#modalAuto">
                                    Agregar Auto
                                </button>
                        <a href="<?=base_url('servicios_autos')?>"
                        class="btn btn-secondary btn-sm">
                        Regresar
                    </a>
                    </div>
                </div>
                <div class="card-body">
                    <table id="tablaAutos" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Alias</th>
                            <th>Placas</th>
                            <th>Marcas</th>
                            <th>Modelo</th>
                            <th>Imagenes</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($autos)):?>
                            <?php foreach ($autos as $auto):?>
                                <tr>
                                    <td>
                                        <?=esc($auto['alias'])?>
                                    </td>
                                    <td>
                                        <?=esc($auto['placas'])?>
                                    </td>
                                    <td>
                                        <?=esc($auto['marca'])?>
                                    </td>
                                    <td>
                                        <?=esc($auto['modelo'])?>
                                    </td>
                                    <style>
                                        .img-auto{
                                            width : 100px;
                                            height: 100px;
                                            object-fit: cover;
                                        }
                                    </style>
                                    <td>
                                        <div class="d-flex justify-content-center flex-wrap"> <?php if (!empty($auto['imagenes'])):?>
                                            <?php foreach($auto['imagenes'] as $imagen):?>
                                    <img src="<?=base_url('public/uploads/autos/'. $imagen['imagen'])?>"class="img-auto img-thumbnail m-1" style="cursor:pointer;" onclick="verImagen(this)">
                                        <?php endforeach;?>
                                    <?php else:?>
                                            Sin Imagenes
                                        <?php endif;?>
                                    </div>
                                    </td>
                                    <td>
                                        <?php if($auto['activo']==1):?>
                                            <span class="badge badge-success">
                                                Activo
                                            </span>
                                            <?php else:?>
                                                <span class="badge badge-danger">
                                                    Inactivo
                                                </span>
                                                <?php endif;?>
                                            </td>
                                            <td>
                                                <a href="<?= base_url('autos/edit/'.$auto['id'])?>"class="btn btn-warning btn-sm">
                                                    Editar
                                                </a>
                                                
                                                <?php if($auto['activo']==1):?>
                                                    <a href="<?= base_url('autos/baja/'.$auto['id'])?>" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('¿Desea dar de baja este vehiculo?')">
                                                        Dar de baja
                                                    </a>
                                                    <?php else:?>
                                                        <a href="<?= base_url('autos/reactivar/'.$auto['id'])?>" class="btn btn-success btn-sm"
                                                        onclick="return confirm('¿Desea reactivar este vehiculo?')">
                                                        Reactivar
                                                        </a>
                                                        <?php endif;?>
                                                        <a href="<?= base_url('autos/detalles/'.$auto['id'])?>" class="btn btn-info btn-sm">
                                                            Detalles
                                                        </a>
                                                        <a href="<?=base_url('servicios_autos/programar/'.$auto['id'])?>" class="btn btn-warning btn-sm">Programar Servicio</a>
                                                    </td>
                                                    </tr>
                                                    <?php endforeach;?>
                                                    <?php else:?>
                                                        <tr>
                                                            <td colspan="5"
                                                            class="text-center">
                                                            No existen autos registrados
                                                        </td>
                                                        </tr>
                                                        <?php endif;?>
                                                    </tbody>
                                                    </table>
                                                    </div>
                                                    </div>
                                                    </div>
                                                    </div>
                                                    </div>
                                                    </section>

</div>

<div class="modal fade" id="modalAuto" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <form method="POST" 
            enctype="multipart/form-data"
            action="<?= base_url('autos/store') ?>">

                <div class="modal-header">
                    <h5 class="modal-title">Agregar Auto</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="from-group">
                        <label>Alias</label>
                        <input type="text" name="alias" class="form-control">
                    </div>
                
                    <div class="form-group">
                        <label>Placas</label>
                        <input type="text" name="placas" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Marca</label>
                        <input type="text" name="marca" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Modelo</label>
                        <input type="text" name="modelo" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Imagenes</label>
                        <input type="file" name="imagenes[]" multiple accept="image/*" class= "form-control-file">
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Guardar
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>


<div class="modal fade" id="modalImagen" tabindex="-1" role="dialog">
    <div class = "modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body text-center p-2">
                <img id="imgPreview" src="" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<script>
    function verImagen(elemento){
        let src = elemento.getAttribute('src');
        document.getElementById('imgPreview').setAttribute('src', src);
        $('#modalImagen').modal('show');
    }
</script>


<script>
    $(function(){
        $('#tableAutos').DataTable({
            order:[],
            responsive: true,
            lenghtChange: true,
            autoWidth: false
        });
    });
</script>

<?= $this->include('layouts/footerListas') ?>

