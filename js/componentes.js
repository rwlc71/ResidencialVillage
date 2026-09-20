async function aceita(dado) {
    const caminhoCompleto = window.location.pathname;
    const site = '/' + caminhoCompleto.split('/')[1];
    let url = site + '/funcoes/gravar_consentimento.php?dado=' + dado;
    console.log(url);

    try {
        const ret = await fetch(url);
        const r = await ret.json();

        if (r.status === 'success') {
            alert("Você consentiu com nossa Política de Privacidade. Obrigado!");
            // Redireciona para a página proprietarios.php
            window.location.href = 'proprietarios.php';
        } else {
            alert("Houve um erro ao processar o consentimento.");
        }
    } catch (error) {
        alert("Ocorreu um erro. Por favor, tente novamente.");
    }
}

async function naoaceita() {
    alert("Você recusou o consentimento. O acesso será interrompido.");
    // Redireciona para a página autentica.php
    window.location.href = 'autentica.php';
}

function aplicarMascaraCpfCnpj(input) {
    // Adiciona um event listener para o evento de input (digitação)
    input.addEventListener('input', function () {
        // Remove todos os caracteres que não são dígitos
        let valor = input.value.replace(/\D/g, '');

        // Aplica a máscara de CPF (###.###.###-##) para até 11 dígitos
        if (valor.length <= 11) {
            valor = valor.replace(/(\d{3})(\d)/, '$1.$2');
            valor = valor.replace(/(\d{3})(\d)/, '$1.$2');
            valor = valor.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
        }
        // Aplica a máscara de CNPJ (##.###.###/####-##) para mais de 11 dígitos
        else {
            valor = valor.replace(/(\d{2})(\d)/, '$1.$2');
            valor = valor.replace(/(\d{3})(\d)/, '$1.$2');
            valor = valor.replace(/(\d{3})(\d)/, '$1/$2');
            valor = valor.replace(/(\d{4})(\d{1,2})$/, '$1-$2');
        }

        // Atualiza o valor do input com a máscara aplicada
        input.value = valor;
    });
}

function aplicarMascaraData(input) {
    // Adiciona um event listener para o evento de input (digitação)
    input.addEventListener('input', function () {
        // Remove todos os caracteres que não são dígitos
        let valor = input.value.replace(/\D/g, '');

        // Limita a quantidade de caracteres para 8 (ddmmaaaa)
        if (valor.length > 8) {
            valor = valor.slice(0, 8);
        }

        // Aplica a máscara de data (dd/mm/yyyy)
        if (valor.length >= 5) {
            valor = valor.replace(/(\d{2})(\d{2})(\d{1,4})/, '$1/$2/$3');
        } else if (valor.length >= 3) {
            valor = valor.replace(/(\d{2})(\d{1,2})/, '$1/$2');
        }

        // Atualiza o valor do input com a máscara aplicada
        input.value = valor;
    });
}

function aplicarMascaraTelefone(input) {
    // Adiciona um event listener para o evento de input (digitação)
    input.addEventListener('input', function () {
        // Remove todos os caracteres que não são dígitos
        let valor = input.value.replace(/\D/g, '');

        if (valor.length > 0) {
            valor = valor.replace(/^(\d{2})(\d)/g, '($1) $2'); // Adiciona o parêntese
        }

        if (valor.length > 9) {
            valor = valor.replace(/(\d{5})(\d)/, '$1-$2'); // Adiciona o hífen após o quinto dígito
        }

        // Atualiza o valor do input com a máscara aplicada
        input.value = valor;
    });
}

function openModal(imageSrc) {
    // Define a imagem no modal
    const modal = document.getElementById('myModal');
    const modalImg = document.getElementById('imgModal');
    imgModal.src = imageSrc; // Define a imagem a ser exibida no modal
    modal.style.display = 'flex'; // Exibe o modal com flexbox
}

function closeModal() {
    // Fecha o modal
    const modal = document.getElementById('myModal');
    modal.style.display = 'none';
}