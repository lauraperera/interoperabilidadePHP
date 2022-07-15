# interoperabilidadePHP

### Implementando interoperabilidade de sistemas com PHP

Neste projeto é realizada a conexão entre 3 sistemas de farmácia, onde os três sistemas publicam um serviço web que faz a verificação do histórico das 3 compras mais recentes de um cliente, a partir disso, os sistemas consomem o serviço publicado pelas demais farmácias da na hora de cadastrar uma venda e em caso do produto vendido ter sido vendido para o **mesmo** cliente em alguma das **outras** farmáticas da rede, o cliente recebe 10% de desconto. 

## Rodar o projeto
```
$ php -S localhost:$porta
```
