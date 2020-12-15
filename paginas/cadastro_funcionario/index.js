window.onload = function () {
    buttons = document. querySelectorAll("div button"); // selecionando todos os botões que aparecem na barra de navegação
    for(let button of buttons){
        button.addEventListener("click",changeTab); // associando a chamada changeTab ao click do botão.
    }
    openTab(0); // ativando a primeira section que no caso é Ciencia da Computação.
}

function changeTab(e){ // definindo a função changeTab com parametro e.
    const botaoAcionado = e.target; // achando o botão em particular que disparou o evento.
    const liDoBotao = botaoAcionado.parentNode; //acessando o nó pai referente à Li.
    const nodes = Array.from(liDoBotao.parentNode.children);//trasnformando a lista de nós filhos do ul em array.
    const indice = nodes.indexOf(liDoBotao);//descobrindo a posição do li em particular na lista.
    openTab(indice);//chamando a função openTab com o indice descoberto.

}

function openTab(i){//função para tornar visível ou esconder o section referente ao indice descoberto.
    const tabActive = document.querySelector(".tabActive");//buscando a classe tabActive.
    if(tabActive !== null) // se for diferente de null, a class é substituida por "" tornando assim o section visível.
    tabActive.className= "";

    const buttonActive = document.querySelector(".buttonActive");//buscando a classe buttonActive.
    if(buttonActive !== null)// se for diferente de Null a classe é substituída por "" tornando assim o section visível.
    buttonActive.className = "";

    document.querySelectorAll(".tabs section")[i].className = "tabActive"; // buscando em todos os sections dentro do div e utilizando o indice para selecionar a section em particular e alterar a class para tabActive.
    document.querySelectorAll("div button")[i].className = "buttonActive";// buscando em todos os buttons dentro do nav e utilizando o indice para selecionar o button em particular e alterar a class para buttonActive.
}
