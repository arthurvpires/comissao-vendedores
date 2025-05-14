<h2>Olá {{ env('ADMIN_NAME') }},</h2>
<p>Segue a soma das vendas efetuadas no dia:</p>

<h1>
    R$ {{ number_format($sales / 100, 2, ',', '.') }}
</h1>
