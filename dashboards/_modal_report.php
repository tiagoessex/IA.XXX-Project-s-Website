<!--
    INCLUDED IN:
        /dashboards/geraldashboard.php
        /dashboards/denunciasdashboard.php
        /dashboards/entidadesdashboard.php
        /dashboards/fiscalizacoesdashboard.php
-->
<!-- REPORT MESSAGE MODAL -->
<div class="modal" id="modal_report">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title modal-title-message">Relatório</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-content-report">
                <div class="modal-body modal-body-report">
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe id="iframe_report" class="embed-responsive-item" allowfullscreen name="print_report"></iframe>
                    </div>
                </div>
            </div>

            <div class="modal-footer">                
                <button type="button" class="btn btn-primary" onclick="printReport();">Print</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Ok</button>
            </div>

        </div>
    </div>
</div>
<!-- END OF REPORT MODAL -->

<script>
    function createReport(msg) {
        var doc = document.getElementById("iframe_report").contentWindow.document;
        doc.open();
        doc.write(msg);
        doc.close();
    }

    // since the report occupies the entire page, then
    // there's no need for fancy crap. just print the entire page.
    function printReport() {
        window.frames["print_report"].focus();
        window.frames["print_report"].print();
    };

    // since the report is create in an entire new page (iframe)
    // this function returns the reference of a tag in that page
    // so, similar to jquery $(tag)
    function getReportReference(id_or_class) {
        return $("#iframe_report").contents().find(id_or_class);
    }

    function getReportCanvasContext(id) {
        return getReportReference(id)[0].getContext('2d');
    }


</script>