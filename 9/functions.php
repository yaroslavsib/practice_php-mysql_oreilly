<?php

// Эта функция строит поисковый запрос из поисковых слов и сортирует результаты
function build_query($user_search, $sort) {
	$search_query  = "select * from riskyjobs";
	// Убераем запятые из поисковых слов, если они есть
	$clean_search_words = str_replace(',', '', $user_search);
	// Извлекаем слова поиска из строки в массив
	$search_words = explode(' ', $clean_search_words);
	$final_search_words = array();
	if (count($search_words) > 0) {
		foreach ($search_words as $word) {
			if (!empty($word)) { // Не учитываем образовавшиеся пробелы
				$final_search_words[] = $word;
			}
		}
	}
	// Генерируем часть запроса WHERE
	$where_list = array();
	if (count($final_search_words) > 0) {
		foreach ($final_search_words as $final_word) {
			$where_list[] = "description LIKE '%$final_word'";
		}
	}
	$where_clause = implode(' or ', $where_list);
	// Добавляем слово WHERE в запрос
	if (!empty($where_clause)) {
		$search_query .= " where $where_clause";
	}

	// Сортировка поискового запроса
	switch ($sort) {
		// По возрастанию Работа
		case 1:
			$search_query .= " ORDER BY title";
			break;
		// По убыванию Работа
		case 2:
			$search_query .= " ORDER BY title DESC";
			break;
		// По возрастанию Штат
		case 3:
			$search_query .= " ORDER BY state";
			break;
		// По убыванию Штат
		case 4:
			$search_query .= " ORDER BY state DESC";
			break;
		// По возрастанию Дата
		case 5:
			$search_query .= " ORDER BY date_posted";
			break;
		// По убыванию Дата
		case 6:
			$search_query .= " ORDER BY date_posted DESC";
			break;
		default:
			// Без сортировки
	}
	return $search_query;

}

// Эта функция делает ссылки на заголовках таблицы с настройками сортировки
function generate_sort_links($user_search, $sort) {
  $sort_links = '';

  switch ($sort) {
    case 1:
	  $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=2">Работа</a></td><td>Описание</td>';
	  $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=3">Штат</a></td>';
	  $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=5">Дата</a></td>';
	  break;
	case 3:
	  $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=1">Работа</a></td><td>Описание</td>';
	  $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=4">Штат</a></td>';
	  $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=3">Дата</a></td>';
	  break;
	case 5:
	  $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=1">Работа</a></td><td>Описание</td>';
	  $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=3">Штат</a></td>';
	  $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=6">Дата</a></td>';
	  break;
		
	default:
	  $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=1">Работа</a></td><td>Описание</td>';
	  $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=3">Штат</a></td>';
	  $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=5">Дата</a></td>';
	  break;
  }
  return $sort_links;	
}

// Эта функция построит навигацию страниц, пагинатор
function generate_page_links($user_search, $sort, $cur_page, $num_pages) {
  $page_links = '';

  // Если страница не первая, генерируем ссылку "Предыдущая"
  if ($cur_page > 1) {
    $page_links .= '<a href="' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=' . $sort . '&page=' . ($cur_page - 1) . '"><-</a> ';
  }
  else {
    $page_links .= '<- ';	
  }

  // Генерируем ссылки на страницы в цикле
  for ($i=1; $i <= $num_pages; $i++) {
  	if ($cur_page == $i) {
  	  $page_links .= ' ' . $i;
  	}
  	else {
  	  $page_links .= ' <a href="' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=' . $sort . '&page=' . $i . '"> ' . $i . '</a>';
  	}
  }

  // Если страница не последняя, генерируем ссылку "Следующая"
  if ($cur_page < $num_pages) {
  	$page_links .= ' <a href="' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=' . $sort . '&page=' . ($cur_page + 1) . '">-></a>';
  }
  else {
  	$page_links .= ' ->';
  }

  return $page_links;
}