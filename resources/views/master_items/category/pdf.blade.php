<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">

    <title>Category PDF</title>

    <style>

        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        h3 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            vertical-align: top;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;

            text-align: right;
            font-size: 10px;
        }

        .badge {
            display: inline-block;
            padding: 3px 6px;
            margin: 2px;
            background: #dbeafe;
            border: 1px solid #93c5fd;
            border-radius: 3px;
        }

    </style>

</head>

<body>

    <h3>Category Data</h3>

    <table>

        <thead>

            <tr>

                <th width="20%">Kode</th>
                <th width="30%">Category</th>
                <th>Items</th>

            </tr>

        </thead>

        <tbody>

            @foreach ($categories as $category)

                <tr>

                    <td>
                        {{ $category->kode }}
                    </td>

                    <td>
                        {{ $category->name }}
                    </td>

                    <td>

                        @forelse($category->masterItems as $item)

                            <span class="badge">
                                {{ $item->nama }}
                            </span>

                        @empty

                            <span>
                                No Item
                            </span>

                        @endforelse

                    </td>

                </tr>

            @endforeach

        </tbody>

    </table>

    <div class="footer">

        Dicetak:
        {{ now()->format('d-m-Y H:i:s') }}

    </div>

</body>

</html>