@extends('emails.layout')

@section('title', 'Сброс пароля')

@section('content')
    <div class="reset-password">
        <table border="0" cellpadding="0" cellspacing="0" width="450" style="width:450px; max-width:450px; margin:0 auto; table-layout: fixed">
            <tr>
                <td>
                    <table align="center" border="0" cellpadding="0" cellspacing="0" width="450" style="border-collapse: collapse; background-color: #ffffff; box-shadow: 0px 4px 15px 0px rgba(0, 0, 0, 0.1);">
                        <tr>
                            <td>
                                <!-- Main Content Table with Padding -->
                                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                    <tr>
                                        <td style="padding: 24px 24px 0 24px;">
                                            <table width="100%">
                                                <tr>
                                                    <td align="left" style="font-size: 16px; font-weight: bold; color: #381a1c;">
                                                        <span style="color: #2B338A;">HUMBLE</span><span style="color: #B32D91;">BRAINS</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td align="center" style="padding: 20px 30px 40px 30px;">
                                                        <img src="{{ asset('images/mail.png') }}" alt="Verification Image" style="max-height: 210px; width: auto;">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td align="left" style="padding-bottom: 8px; font-size: 24px; font-weight: bold; color: #2B338A;">
                                                        Сброс пароля
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td align="left" style="padding-bottom: 36px; font-size: 14px; color: #6771A2;">
                                                        Нажмите на кнопку ниже, чтобы сбросить текущий пароль.
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td align="center" style="padding-bottom: 60px;">
                                                        <a href="{{ $url  }}" style="background-color: #4149A0; color: #ffffff; padding: 10px 24px; text-decoration: none; font-weight: bold; border-radius: 100px; display: inline-block;">
                                                            Сбросить
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="padding-bottom: 24px; font-size: 14px; color: #6771A2;">
                                                        Или используйте ссылку:
                                                        <a href="{{ $url  }}" style="color: #B32D91; text-decoration: none; word-break: break-all;">
                                                            {{ $url }}
                                                        </a>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>

                                <!-- Footer Table without Padding -->
                                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse; background-color: #EAEBF6;">
                                    <tr>
                                        <td align="left" style="padding: 12px 0 12px 24px; width: 100%; position: relative;">
                                            <div style="font-size: 12px; font-weight: 600; color: #2B338A;">humblerat</div>
                                            <div style="font-size: 10px; color: #6771A2;">{{ date('Y') }}</div>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
@endsection
