<table border="0" cellpadding="0" cellspacing="0" width="600" style="background-color:#ffffff;border:1px solid #dedede;border-radius:3px">
    <tbody>
        <tr>
            <td align="center" valign="top">
                <table border="0" cellpadding="0" cellspacing="0" width="600"  style="background-color:#914BC0;color:#ffffff;border-bottom:0;font-weight:bold;line-height:100%;vertical-align:middle;font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif;border-radius:3px 3px 0 0">
                    <tbody>
                        <tr>
                            <td style="padding:36px 48px;display:block">
                                <h1 style="font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif;font-size:30px;font-weight:300;line-height:150%;margin:0;text-align:left;color:#ffffff">
                                    <span class="il">Nuevo Caso CRM {{ $case }}</span> / Quipus seguros
                                </h1>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
        <td align="center" valign="top">
            <table border="0" cellpadding="0" cellspacing="0" width="600" >
                <tbody>
                    <tr>
                        <td valign="top"  style="background-color:#ffffff">
                            <table border="0" cellpadding="20" cellspacing="0" width="100%">
                                <tbody>
                                    <tr>
                                        <td valign="top" style="padding:48px 48px 32px">
                                            <div style="color:#636363;font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif;font-size:14px;line-height:150%;text-align:left">
                                                <p style="margin:0 0 16px">Hola. Se ha creado un nuevo caso de CRM con # <a href="{{ $url }}/crm/cases/{{ $case }}">{{ $case }}</a> y se te ha asignado como responsable. Para ver mas detalles da clic <a href="{{ $url }}/crm/cases/{{ $case }}">aquí</a>.</p>
                                                <p><b>Información adicional</b></p>
                                                <p><b>Creador(@) del caso:</b> "{{ $creator_case }}"<br>
                                                <p><b>Descripción:</b> "{{ $note }}"</p>
                                                <br><br>
                                                <h4>Team Amautta Systems :D</h4>

                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>
