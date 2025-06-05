   
    @auth
                    @if (Auth::user()->tipo_usuario !== 'admin')
                    <script>
                        window.location.href = "/";
                    </script>
                @endif
                @endauth
                <?php


    
    ?>

    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Adicionar Jogo - Admin</title>
        
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">
        <style>
            * { margin: 0; padding: 0; box-sizing: border-box; }

            body {
                font-family: 'Outfit', sans-serif;
                background: linear-gradient(to right, #1e1e2f, #151521);
                color: #f0f0f0;
                padding: 20px;
            }

            main {
                max-width: 700px;
                margin: auto;
                background: rgba(255, 255, 255, 0.05);
                padding: 20px;
                border-radius: 20px;
                box-shadow: 0 0 20px rgba(0,0,0,0.3);
                animation: fadeInUp 0.8s ease-out;
            }

            h2 {
                text-align: center;
                color: #facc15;
                margin-bottom: 20px;
            }

            form input, form textarea, form button {
                display: block;
                width: 100%;
                margin: 10px 0;
                padding: 10px;
                border: none;
                border-radius: 10px;
            }

            form input, form textarea {
                background-color: #2c2c3d;
                color: #fff;
            }

            form button {
                background-color: #facc15;
                color: #000;
                font-weight: bold;
                cursor: pointer;
                transition: background-color 0.3s;
            }

            form button:hover {
                background-color: #00aacc;
            }

            p {
                text-align: center;
                margin-top: 15px;
                color: #00ffae;
                font-weight: bold;
            }

            @keyframes fadeInUp {
                from {opacity: 0; transform: translateY(40px);}
                to {opacity: 1; transform: translateY(0);}
            }

            .voltar {
                position: absolute;
                top: 20px;
                left: 20px;
                padding: 8px 14px;
                background: #facc15;
                color: #000;
                text-decoration: none;
                border-radius: 10px;
                font-weight: bold;
                transition: all 0.3s ease;
            }

            .voltar:hover {
                background-color: #00aacc;
                transform: scale(1.05);
                box-shadow: 0 0 10px #facc1566;
            }

            #gameDropdown {
        display: none;
        position: absolute;
        width: 60%;
        max-height: 250px;
        background-color: #2c2c3d;
        overflow-y: auto;
        border-radius: 10px;
        z-index: 1000;
        margin-top: 5px;
    }

    .dropdown-item {
        display: flex;
        align-items: center;
        padding: 10px;
        cursor: pointer;
        color: #f0f0f0;
         transition: background-color 0.3s ease-in-out;
    }

    .dropdown-item:hover {
        background-color: #444455;
    }
    .dropdown-item img {
        width: 20%;
        height: 15%;
        border-radius: 5px;
        margin: 0 10px 0 0;

        object-fit: contain;
    }

    .no-result {
        padding: 10px;
        color: #aaa;
    }
     .plataforma-dropdown {
        display: none;
        position: absolute;
        background-color: #2c2c3d;
        border-radius: 10px;
        width: 300px;
        max-height: 200px;
        overflow-y: auto;
        z-index: 1000;
        margin-top: 5px;
    }
    .plataforma-dropdown-item {
        padding: 10px;
        cursor: pointer;
        color: #f0f0f0;
        transition: background-color 0.3s ease;
    }
    .plataforma-dropdown-item:hover {
        background-color: #444455;
    }
    .plataforma-chips-container {
        display: flex;
        flex-wrap: wrap;
        margin-top: 10px;
        gap: 5px;
    }
    .plataforma-chip {
        background-color: #facc15;
        color: #000;
        border-radius: 20px;
        padding: 5px 10px;
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 14px;
    }
    .plataforma-chip button {
        background: transparent;
        border: none;
        color: #000;
        cursor: pointer;
        font-weight: bold;
    }
        </style>
        <script>
            function mostrarBotao() {
        const campoTexto = document.getElementById('titulo');
        const botaoBuscar = document.getElementById('buscarButton');

        if (campoTexto.value.trim() !== '') {
            botaoBuscar.style.display = 'block';
        } else {
            botaoBuscar.style.display = 'none';
        }
    }

    function buscarJogo() {
        const titulo = document.getElementById('titulo').value;
        const dropdown = document.getElementById('gameDropdown');
        dropdown.innerHTML = ''; // Limpa os resultados anteriores

        if (titulo.trim() !== '') {
            fetch(`http://localhost:5000/search?game_name=${titulo}`)
                .then(response => response.json())
                .then(data => {
                    if (data.games && data.games.length > 0) {
                        dropdown.style.display = 'block'; // Exibe o dropdown
                        data.games.forEach(game => {
                            const option = document.createElement('div');
                            option.classList.add('dropdown-item');
                            option.innerHTML = `
                                <img src="${game.cover}" alt="${game.name}">
                                <span>${game.name}</span>
                            `;
                            option.onclick = () => preencherCampos(game); // Ao clicar, preenche os campos
                            option.dataset.cover = game.cover;
                            dropdown.appendChild(option);
                        });
                    } else {
                        dropdown.innerHTML = '<div class="no-result">Nenhum jogo encontrado.</div>';
                        dropdown.style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Erro ao buscar o jogo:', error);
                    dropdown.innerHTML = '<div class="no-result">Ocorreu um erro ao buscar os jogos.</div>';
                    dropdown.style.display = 'block';
                });
        }
    }
    function formatDate(dateStr) {
    // Exemplo: "12 Dez, 2023" ou "Dec 12, 2023"
    const date = new Date(dateStr);
    if (isNaN(date)) return '';
    return date.toISOString().split('T')[0];
    }

    function preencherCampos(game) {
        document.querySelector('input[name="titulo"]').value = game.name;
        document.querySelector('input[name="imagem_url"]').value = game.cover;

        fetch(`http://localhost:5000/game_details?appid=${game.appid}`)
        .then(response => response.json())
        .then(details => {
            document.querySelector('textarea[name="descricao"]').value = details.description || '';
            if (details.release_date) {
                document.querySelector('input[name="data_lancamento"]').value = formatDate(details.release_date);
            }
            document.querySelector('input[name="desenvolvedora"]').value = details.developers || '';
            document.querySelector('input[name="distribuidora"]').value = details.publishers || '';
        }).catch(error => console.error('Erro ao buscar detalhes do jogo:', error));
        const dropdown = document.getElementById('gameDropdown');
        dropdown.style.display = 'none'; 
    }
    

    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('gameDropdown');
        const input = document.getElementById('titulo');

        if (!dropdown.contains(event.target) && event.target !== input) {
            dropdown.style.display = 'none';
        }
    });

        </script>
    </head>
    <body>
    <a href="/" class="voltar">Voltar</a>
        <main>
            <h2>Adicionar Novo Jogo</h2>
            <form method="POST">
                @csrf
                <input type="text" name="titulo" id="titulo" placeholder="Título do jogo" oninput="mostrarBotao()" required>
                <button 
                type="button" 
                id="buscarButton" 
                style="display: none;" 
                onclick="buscarJogo()">Buscar</button>
                <div id="gameDropdown"></div>
                <input type="text" name="imagem_url" placeholder="URL da imagem de capa">
                <input type="text" name="desenvolvedora" placeholder="Desenvolvedora (ex: Ubisoft)">
                <input type="text" name="distribuidora" placeholder="Distribuidora (ex: Microsoft)">
                <input type="date" name="data_lancamento" required>
                <textarea name="descricao" placeholder="Descrição do jogo"></textarea>
                <label for="plataformaButton">Plataformas:</label>
<button type="button" id="plataformaButton">Selecionar Plataforma</button>
<div id="plataformaDropdown" class="plataforma-dropdown"></div>

<div id="plataformasSelecionadas" class="plataforma-chips-container"></div>

<input type="hidden" name="plataformas" id="plataformasInput">


                <button type="submit">Salvar</button>
            </form>
            <div id="gameResults" style="margin-top: 50px; display: flex-col;"></div>
            

        </main>

    
    </body>
    <header>
        <script>
         const plataformas = JSON.parse('{!! json_encode($plataformas) !!}');


    const plataformaButton = document.getElementById('plataformaButton');
    const plataformaDropdown = document.getElementById('plataformaDropdown');
    const plataformasSelecionadas = document.getElementById('plataformasSelecionadas');
    const plataformasInput = document.getElementById('plataformasInput');

    const plataformasSelecionadasIds = [];

    plataformaButton.addEventListener('click', () => {
      plataformaDropdown.innerHTML = '';
      plataformas.forEach(p => {
        if (!plataformasSelecionadasIds.includes(p.idPlataformas)) {
          const item = document.createElement('div');
          item.classList.add('plataforma-dropdown-item');
          item.textContent = p.plataforma;
          item.addEventListener('click', () => adicionarPlataforma(p));
          plataformaDropdown.appendChild(item);
        }
      });
      plataformaDropdown.style.display = 'block';
    });

    function adicionarPlataforma(plataforma) {
      if (!plataformasSelecionadasIds.includes(plataforma.idPlataformas)) {
        plataformasSelecionadasIds.push(plataforma.idPlataformas);

        const chip = document.createElement('div');
        chip.classList.add('plataforma-chip');
        chip.textContent = plataforma.plataforma;

        const removeBtn = document.createElement('button');
        removeBtn.textContent = 'x';
        removeBtn.addEventListener('click', () => removerPlataforma(plataforma.idPlataformas, chip));
        chip.appendChild(removeBtn);

        plataformasSelecionadas.appendChild(chip);

        atualizarInput();
      }
      plataformaDropdown.style.display = 'none';
    }

    function removerPlataforma(id, chip) {
      const index = plataformasSelecionadasIds.indexOf(id);
      if (index > -1) {
        plataformasSelecionadasIds.splice(index, 1);
        chip.remove();
        atualizarInput();
      }
    }

    function atualizarInput() {
      plataformasInput.value = JSON.stringify(plataformasSelecionadasIds);
    }

    document.addEventListener('click', (event) => {
      if (!plataformaDropdown.contains(event.target) && event.target !== plataformaButton) {
        plataformaDropdown.style.display = 'none';
      }
    });
</script>
    </header>
    </html>
