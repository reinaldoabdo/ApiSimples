# ApiSimples - Api Json PHP Simples e genérica

*** ATENÇÃO - Desenvolvida para estudos, não há nenhuma garantia de sua segurança ou seu adequado funcionamento ***

Utilizada para consumir classes e métodos permitidos no lugar de serviços.

# Exemplo de consumo em JavaScript da API

Este é um exemplo simples de como consumir uma API em JavaScript.

## Uso

```javascript
// Assumindo que você tenha um endpoint onde a API está hospedada
const apiUrl = 'https://exemplo.com/api';

// Crie os dados para a requisição da API
const dadosRequisicao = {
  class: 'pessoa',
  method: 'getInfo',
  params: {
    id: 1
  }
};

// Faça uma requisição POST para o endpoint da API
fetch(apiUrl, {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json'
  },
  body: JSON.stringify(dadosRequisicao)
})
  .then(response => response.json())
  .then(data => {
    // Manipule a resposta da API
    console.log(data);
  })
  .catch(error => {
    // Manipule quaisquer erros que ocorreram durante a requisição da API
    console.error('Erro:', error);
});



