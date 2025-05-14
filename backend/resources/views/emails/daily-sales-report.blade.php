<h2>Olá {{ $sellerName }}!</h2>

@if ($quantity > 0)
    <p>Segue o resumo das suas vendas de hoje:</p>

<ul>
    <li>Quantidade de vendas: <strong>{{ $quantity }}</strong></li>
    <li>Valor total: <strong>R$ {{ number_format($total / 100, 2, ',', '.') }}</strong></li>
    <li>Comissão total: <strong>R$ {{ number_format($commission / 100, 2, ',', '.') }}</strong></li>
    
    </ul>

    <p>Bom trabalho!</p>
@else
    <p>Hoje não foi registrado nenhuma venda.</p>
@endif
