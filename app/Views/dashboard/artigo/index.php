<div class="flex gap-6 w-full h-screen">
  <session class="w-full h-full bg-white p-6 border border-slate-200 rounded-lg">
    <table class="w-full table-fixed text-sm text-left text-gray-500">
      <thead class="text-xs text-gray-700 uppercase bg-gray-50">
        <tr class="bg-white border-b hover:bg-gray-50">
          <th scope="col" class="px-6 py-3 w-10">
            <input type="checkbox" name="artigo" id="artigoTodos" class="flex items-center w-10">
          </th>
          <th scope="col" class="px-6 py-3 w-16">ID</th>
          <th scope="col" class="px-6 py-3 w-24">Status</th>
          <th scope="col" class="px-6 py-3 w-56">Título</th>
          <th scope="col" class="px-6 py-3 w-32">Categoria</th>
          <th scope="col" class="px-6 py-3 w-32">Criado</th>
          <th scope="col" class="px-6 py-3 w-32">Modificado</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($artigos as $chave => $linha) : ?>
          <tr class="bg-white border-b hover:bg-gray-50">
            <td class="whitespace-nowrap p-6 w-10">
              <input type="checkbox" name="artigo" id="artigoUnico" class="w-10">
            </td>

            <?php foreach ($linha as $subChave => $subLinha) : ?>

              <?php if ($subChave == 'Artigo.id') { ?>
                <td class="whitespace-nowrap p-6 w-16">#<?php echo $subLinha ?></td>
              <?php } ?>

              <?php if ($subChave == 'Artigo.ativo') { ?>
                <td class="whitespace-nowrap p-6 uppercase text-xs w-24"><?php echo $subLinha == 1 ? 'Ativo' : 'Inativo' ?></td>
              <?php } ?>

              <?php if ($subChave == 'Artigo.titulo') { ?>
                <td class="whitespace-nowrap p-6 w-56"><?php echo $subLinha ?></td>
              <?php } ?>

              <?php if ($subChave == 'Categoria.nome') { ?>
                <td class="whitespace-nowrap p-6 w-32"><?php echo $subLinha ?></td>
              <?php } ?>

              <?php if ($subChave == 'Artigo.criado') { ?>
                <td class="whitespace-nowrap p-6 w-32"><?php echo $subLinha ?></td>
              <?php } ?>

              <?php if ($subChave == 'Artigo.modificado') { ?>
                <td class="whitespace-nowrap p-6 w-32"><?php echo $subLinha ?></td>
              <?php } ?>
            <?php endforeach; ?>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </session>
</div>