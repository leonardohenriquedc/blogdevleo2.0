# {{ $siteName }}

> Blog sobre desenvolvimento e tecnologia escrito por Leonardo Henrique.

O blog reúne artigos sobre programação, projetos e experiências de
desenvolvimento. Todo o conteúdo é publicado em português (pt-BR).

## Seções

- [Início]({{ $siteUrl }})
- [Artigos]({{ $siteUrl }})

## Artigos

@forelse ($posts as $post)
- [{{ $post['title'] }}]({{ $siteUrl }}/get/{{ $post['slug'] }})
@empty
- Nenhum artigo publicado ainda.
@endforelse
