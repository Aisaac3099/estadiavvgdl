  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 1.0.0
    </div>
    <strong>Copyright &copy; 2025 <a href="https://videovigilanciagdl.com.mx/">Sistema VVGDL</a>.</strong> Todos los derechos reservados.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="<?= base_url('plugins/jquery/jquery.min.js') ?>"></script>
<!-- Bootstrap 4 -->
<script src="<?= base_url('plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<!-- DataTables  & Plugins -->
<script src="<?= base_url('plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>"></script>
<script src="<?= base_url('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('plugins/datatables-buttons/js/dataTables.buttons.min.js') ?>"></script>
<script src="<?= base_url('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('plugins/jszip/jszip.min.js') ?>"></script>
<script src="<?= base_url('plugins/pdfmake/pdfmake.min.js') ?>"></script>
<script src="<?= base_url('plugins/pdfmake/vfs_fonts.js') ?>"></script>
<script src="<?= base_url('plugins/datatables-buttons/js/buttons.html5.min.js') ?>"></script>
<script src="<?= base_url('plugins/datatables-buttons/js/buttons.print.min.js') ?>"></script>
<script src="<?= base_url('plugins/datatables-buttons/js/buttons.colVis.min.js') ?>"></script>
<!-- AdminLTE App -->
<script src="<?= base_url('dist/js/adminlte.min.js') ?>"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?= base_url('dist/js/demo.js') ?>"></script>
<!-- Page specific script -->
<script>
  $(function () {
    $("#example1").DataTable({
      responsive: true,
      lengthChange: false,
      autoWidth: false,
      pageLength: 50, // Se muestran 50 registros por página
      buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"],
      order: [[0, 'desc']],
      language: {
        decimal:        "",
        emptyTable:     "No hay información",
        info:           "Mostrando _START_ a _END_ de _TOTAL_ registros",
        infoEmpty:      "Mostrando 0 a 0 de 0 registros",
        infoFiltered:   "(Filtrado de _MAX_ registros totales)",
        infoPostFix:    "",
        thousands:      ",",
        lengthMenu:     "Mostrar _MENU_ registros",
        loadingRecords: "Cargando...",
        processing:     "Procesando...",
        search:         "Buscar:",
        zeroRecords:    "No se encontraron registros coincidentes",
        paginate: {
            first:      "Primero",
            last:       "Último",
            next:       "Siguiente",
            previous:   "Anterior"
        },
        buttons: {
            copy: "Copiar",
            csv: "CSV",
            excel: "Excel",
            pdf: "PDF",
            print: "Imprimir",
            colvis: "Columnas"
        }
      }
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    $('#example2').DataTable({
      paging: true,
      lengthChange: false,
      searching: false,
      ordering: true,
      info: true,
      autoWidth: false,
      responsive: true,
      pageLength: 50, // También 50 registros si quieres
      language: {
        decimal:        "",
        emptyTable:     "No hay información",
        info:           "Mostrando _START_ a _END_ de _TOTAL_ registros",
        infoEmpty:      "Mostrando 0 a 0 de 0 registros",
        infoFiltered:   "(Filtrado de _MAX_ registros totales)",
        infoPostFix:    "",
        thousands:      ",",
        lengthMenu:     "Mostrar _MENU_ registros",
        loadingRecords: "Cargando...",
        processing:     "Procesando...",
        search:         "Buscar:",
        zeroRecords:    "No se encontraron registros coincidentes",
        paginate: {
            first:      "Primero",
            last:       "Último",
            next:       "Siguiente",
            previous:   "Anterior"
        },
        buttons: {
            copy: "Copiar",
            csv: "CSV",
            excel: "Excel",
            pdf: "PDF",
            print: "Imprimir",
            colvis: "Columnas"
        }
      }
    });
  });
</script>

<script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-messaging-compat.js"></script>
 

</body>
</html>
