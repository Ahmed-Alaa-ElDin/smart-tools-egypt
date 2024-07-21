<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    {{-- Styles --}}
    <style>
        body {
            font-family: 'XBRiyaz', sans-serif;
            direction: rtl;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #212529;
            background-color: #fff;
        }

        @page {
            footer: page-footer;
        }
    </style>
</head>

<body>
    @foreach ($orders as $order)
        @if (!$loop->first)
            <pagebreak resetpagenum="1" >
        @endif

        <div>
            <header class="flex justify-between align-start">
                <table style="width:100%;">
                    <tr>
                        <td>
                            <div style="font-size: 2rem;font-weight: 700;">
                                {{ $order["user_type"] }}
                            </div>
                        </td>
                        <td rowspan="2" align="left">
                            <img src="{{ public_path('assets/img/logos/smart-tools-logo-text-400.png') }}"
                                alt="Smart Tools Egypt Logo" width="15rem">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div style="margin-top: 20px">
                                <div>
                                    <span style="font-weight:bold;">الاسم:</span>
                                    <span>
                                        {{ $order['user_name'] }}
                                    </span>
                                </div>

                                <div>
                                    <span style="font-weight:bold;">رقم التليفون:</span>
                                    <span>
                                        {{ $order['phone1'] }} - {{ $order['phone2'] }}
                                    </span>
                                </div>

                                <div>
                                    <span style="font-weight:bold;">العنوان:</span>
                                    <span>
                                        {{ $order['address']['details'] ? $order['address']['details'] . ' - ' : '' }}
                                        {{ $order['address']['city']['name']['ar'] }} -
                                        {{ $order['address']['governorate']['name']['ar'] }}
                                        <br>
                                        {{ $order['address']['landmarks'] }}
                                    </span>
                                </div>

                                <div>
                                    <span style="font-weight:bold;">رقم الفاتورة:</span>
                                    <span>{{ $order['id'] }}</span>
                                </div>

                                <div>
                                    <span style="font-weight:bold;">تاريخ الفاتورة:</span>
                                    <span>
                                        {{ Carbon\Carbon::parse($order['created_at'])->format('d/m/Y') }}
                                    </span>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </header>

            <main style="margin-top: 2rem">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead style="border: 1px solid black">
                        <tr style="border: 1px solid black">
                            <th style="border: 1px solid black;padding: 0.5rem 0.75rem;">#</th>
                            <th style="border: 1px solid black;padding: 0.5rem 0.75rem;">البند</th>
                            <th style="border: 1px solid black;padding: 0.5rem 0.75rem;">الموديل</th>
                            <th style="border: 1px solid black;padding: 0.5rem 0.75rem;">الكمية</th>
                            <th style="border: 1px solid black;padding: 0.5rem 0.75rem;">سعر الوحدة</th>
                            <th style="border: 1px solid black;padding: 0.5rem 0.75rem;">القيمة</th>
                            <th style="border: 1px solid black;padding: 0.5rem 0.75rem;">الخصم</th>
                            <th style="border: 1px solid black;padding: 0.5rem 0.75rem;">الإجمالي</th>
                        </tr>
                    </thead>
                    <tbody style="border: 1px solid black;">
                        @foreach ($order['items'] as $item)
                            <tr>
                                <td
                                    style="{{ $loop->last ? 'border-width: 0 1px 1px 1px;' : 'border-width: 0 1px;' }} border-style: solid; border-color: black;padding: 0.5rem 0.75rem; text-align: center">
                                    {{ $loop->iteration }}</td>
                                @if ($item['type'] == 'Product')
                                    <td
                                        style="{{ $loop->last ? 'border-width: 0 1px 1px 1px;' : 'border-width: 0 1px;' }} border-style: solid; border-color: black;padding: 0.5rem 0.75rem;">
                                        {{ $item['name']['ar'] }}</td>
                                @elseif ($item['type'] == 'Collection')
                                    <td
                                        style="{{ $loop->last ? 'border-width: 0 1px 1px 1px;' : 'border-width: 0 1px;' }} border-style: solid; border-color: black;padding: 0.5rem 0.75rem;">
                                        {{ $item['name']['ar'] }} :
                                        <ul>
                                            @foreach ($item['products'] as $collectionItem)
                                                <li>{{ $collectionItem['name']['ar'] }}
                                                    (×{{ $collectionItem['pivot']['quantity'] }})
                                            @endforeach
                                        </ul>
                                    </td>
                                @endif
                                <td
                                    style="{{ $loop->last ? 'border-width: 0 1px 1px 1px;' : 'border-width: 0 1px;' }} border-style: solid; border-color: black;padding: 0.5rem 0.75rem;">
                                    {{ $item['model'] ?? '' }}</td>
                                <td
                                    style="{{ $loop->last ? 'border-width: 0 1px 1px 1px;' : 'border-width: 0 1px;' }} border-style: solid; border-color: black;padding: 0.5rem 0.75rem; text-align:center;">
                                    {{ $item['pivot']['quantity'] }}</td>
                                <td
                                    style="{{ $loop->last ? 'border-width: 0 1px 1px 1px;' : 'border-width: 0 1px;' }} border-style: solid; border-color: black;padding: 0.5rem 0.75rem;">
                                    {{ number_format($item['base_price'], 2) }}</td>
                                <td
                                    style="{{ $loop->last ? 'border-width: 0 1px 1px 1px;' : 'border-width: 0 1px;' }} border-style: solid; border-color: black;padding: 0.5rem 0.75rem;">
                                    {{ number_format($item['pivot']['quantity'] * $item['base_price'], 2) }}</td>
                                <td
                                    style="{{ $loop->last ? 'border-width: 0 1px 1px 1px;' : 'border-width: 0 1px;' }} border-style: solid; border-color: black;padding: 0.5rem 0.75rem;">
                                    {{ number_format($item['pivot']['quantity'] * $item['base_price'] - $item['pivot']['quantity'] * $item['pivot']['price'], 2) }}
                                </td>
                                <td
                                    style="{{ $loop->last ? 'border-width: 0 1px 1px 1px;' : 'border-width: 0 1px;' }} border-style: solid; border-color: black;padding: 0.5rem 0.75rem;">
                                    {{ number_format($item['pivot']['quantity'] * $item['pivot']['price'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>

                    <tfoot>
                        <tr>
                            <td colspan="4"></td>
                            <th style="border: 1px solid black;padding: 0.5rem 0.75rem;" colspan="2">الإجمالي</th>
                            <td style="border: 1px solid black;padding: 0.5rem 0.75rem;" colspan="2">
                                {{ number_format($order['subtotal'], 2) }} ج.م
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4"></td>
                            <th style="border: 1px solid black;padding: 0.5rem 0.75rem;" colspan="2">الخصم</th>
                            <td style="border: 1px solid black;padding: 0.5rem 0.75rem;" colspan="2">
                                {{ number_format($order['discount'], 2) }} ج.م
                            </td>
                        </tr>
                        @if ($order['extra_discount'])
                            <tr>
                                <td colspan="4"></td>
                                <th style="border: 1px solid black;padding: 0.5rem 0.75rem;" colspan="2">خصم إضافي
                                </th>
                                <td style="border: 1px solid black;padding: 0.5rem 0.75rem;" colspan="2">
                                    {{ number_format($order['extra_discount'], 2) }} ج.م
                                </td>
                            </tr>
                        @endif
                        @if ($order['delivery_fees'])
                            <tr>
                                <td colspan="4"></td>
                                <th style="border: 1px solid black;padding: 0.5rem 0.75rem;" colspan="2">رسوم التوصيل
                                </th>
                                <td style="border: 1px solid black;padding: 0.5rem 0.75rem;" colspan="2">
                                    {{ number_format($order['delivery_fees'], 2) }} ج.م
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td colspan="4"></td>
                            <th style="border: 1px solid black;padding: 0.5rem 0.75rem;" colspan="2">الإجمالي بعد
                                الخصم</th>
                            <td style="border: 1px solid black;padding: 0.5rem 0.75rem;" colspan="2">
                                {{ number_format(ceil($order['total']), 2) }} ج.م
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </main>

            <footer style="margin-top: 2rem;">
                <div style="text-align: center; font-weight: bold;">
                    مع تحيات فريق العمل بشركة {{ env('APP_NAME') }}
                </div>

                <div style="text-align: center">
                    للتواصل تليفونيا: {{ config('settings.whatsapp_number') }}
                </div>
            </footer>
        </div>
    @endforeach

    <htmlpagefooter name="page-footer">
        <div style="text-align: left;" dir="ltr">
            page {PAGENO} of {nbpg}
        </div>
    </htmlpagefooter>
</body>

</html>
