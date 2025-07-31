SELECT
    DATE_FORMAT(FROM_UNIXTIME(v.visited_at), '%Y-%m') AS `Месяц (перехода по ссылке)`,
    l.original_url AS `Ссылка`,
    COUNT(v.id) AS `Кол-во переходов`,
    RANK() OVER (PARTITION BY DATE_FORMAT(FROM_UNIXTIME(v.visited_at), '%Y-%m') ORDER BY COUNT(v.id) DESC) AS `Позиция в топе месяца по переходам`
FROM
    visits v
        JOIN
    links l ON v.link_id = l.id
GROUP BY
    `Месяц (перехода по ссылке)`, l.original_url
ORDER BY
    `Месяц (перехода по ссылке)` DESC, `Кол-во переходов` DESC;