
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
<!-- Bootstrap -->
<script src="<?= base_url('plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<!-- jQuery UI -->
<script src="<?= base_url('plugins/jquery-ui/jquery-ui.min.js') ?>"></script>
<!-- AdminLTE App -->
<script src="<?= base_url('dist/js/adminlte.min.js') ?>"></script>
<!-- fullCalendar 2.2.5 -->
<script src="<?= base_url('plugins/moment/moment.min.js') ?>"></script>
<script src="<?= base_url('plugins/fullcalendar/main.js') ?>"></script>
<script src="<?= base_url('plugins/fullcalendar/locales/es.js') ?>"></script> <!--30/09/2025 para agregar idioma español-->
<!-- AdminLTE for demo purposes -->
<script src="<?= base_url('dist/js/demo.js') ?>"></script>
<!-- Page specific script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es', // idioma español (forma moderna)
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: '<?= base_url('eventos') ?>',
        eventDidMount: function(info) {
            info.el.setAttribute('title', info.event.title);
        },
        eventClick: function(info) {
            info.jsEvent.preventDefault();
            window.location.href = info.event.url;
        }
    });

        
        calendar.render();
    });
</script>

<script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-messaging-compat.js"></script>
 

</body>
</html>
