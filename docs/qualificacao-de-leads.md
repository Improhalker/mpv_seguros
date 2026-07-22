# Qualificação de Leads

## Finalidade

A nota de qualificação prioriza o trabalho comercial. Ela não representa probabilidade de conversão.

## Cálculo

Cada critério é avaliado de `1` a `5`:

- capacidade financeira: 25%;
- viabilidade comercial: 30%;
- nível de interesse: 30%;
- disponibilidade: 15%.

O Laravel calcula a média ponderada e a converte para a escala de `0,0` a `10,0`:

```text
nota = média ponderada × 2
```

Se somente alguns critérios forem preenchidos, os seus pesos são redistribuídos proporcionalmente. Sem avaliações, `qualification_score` é `null`. O cliente nunca envia ou altera esse campo diretamente.

## Persistência e apresentação

`qualification_score` usa `numeric(3,1)` no PostgreSQL. A interface apresenta a nota como `8,8 / 10` e aplica as classificações:

| Nota | Classificação |
| --- | --- |
| 0 a 3,9 | Baixa qualificação |
| 4 a 6,9 | Qualificação média |
| 7 a 8,4 | Boa qualificação |
| 8,5 a 10 | Alta qualificação |
