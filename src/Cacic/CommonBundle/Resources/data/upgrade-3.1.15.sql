CREATE OR REPLACE FUNCTION upgrade_3115() RETURNS VOID AS $$
DECLARE
  prop_software record;
  prop record;
  cp record;
  comp record;
  cl record;
  v_id_cp integer;
  v_id_class integer;
BEGIN

  -- Primeiro descobre os nomes de propriedades repetidos
  FOR prop IN select distinct id_class,
                nm_property_name,
                count(id_class_property) as n
              from class_property
              where (nm_property_name is not null
                     or nm_property_name <> '')
              group by id_class,
                nm_property_name
              having count(id_class_property) > 1
              order by id_class,
                n desc
  LOOP

    SELECT min(id_class_property) INTO v_id_cp
    FROM class_property
    where nm_property_name = prop.nm_property_name
          AND id_class = prop.id_class;

    for cp in SELECT id_class_property
              from class_property
              where id_class = prop.id_class
                    and id_class_property <> v_id_cp
                    and (nm_property_name is not null
                         or nm_property_name <> '')

    LOOP

      RAISE NOTICE 'Processando propriedade id = %', cp.id_class_property;

      BEGIN

        RAISE NOTICE 'Atualizando propriedade de software do id = % para o id = %', cp.id_class_property, v_id_cp;

        -- Tenta atualizar para o valor atual. Se der erro apaga
        UPDATE proriedade_software
        SET id_class_property = v_id_cp
        WHERE id_class_property = cp.id_class_property;

      EXCEPTION
        WHEN SQLSTATE '23505' THEN
          RAISE NOTICE 'Propriedade já identificada no id %. Removendo...', cp.id_class_property;

          DELETE FROM proriedade_software
          WHERE id_class_property = cp.id_class_property;
      END;

      RAISE NOTICE 'Processando coletas id = %', cp.id_class_property;

      BEGIN
        RAISE NOTICE 'Atualizando coletas da propriedade id = % para o id = %', cp.id_class_property, v_id_cp;

        -- Tenta atualizar para o valor atual. Se der erro apaga
        UPDATE computador_coleta_historico
        SET id_class_property = v_id_cp
        WHERE id_class_property = cp.id_class_property;

        UPDATE computador_coleta
        SET id_class_property = v_id_cp
        WHERE id_class_property = cp.id_class_property;

        EXCEPTION
        WHEN SQLSTATE '23505' THEN
          RAISE NOTICE 'Propriedade já identificada no id %. Removendo...', cp.id_class_property;

          DELETE FROM computador_coleta_historico
          WHERE id_class_property = cp.id_class_property;

          DELETE FROM computador_coleta
          WHERE id_class_property = cp.id_class_property;
      END;

      RAISE NOTICE 'Processamento finalizado. Excluindo id_class_property = %', cp.id_class_property;

      -- Finalmente apaga a propriedade
      DELETE FROM class_property
      WHERE id_class_property = cp.id_class_property;

    END LOOP; -- Fim do Loop de propriedades

  END LOOP;

  -- Finaliza apagando os nulos
  FOR cp in SELECT id_class_property
            from class_property
            where (id_class_property is NULL
                   or id_class_property = '')
  LOOP

    RAISE NOTICE 'Processando propriedade NULA id = %', cp.id_class_property;

    -- Atualiza todas as coletas para o valor de id_class_property. Se existir apaga
    FOR prop_software IN select *
                         from proriedade_software
                         where id_class_property = cp.id_class_property
    LOOP
      -- Tenta atualizar para o valor atual. Se der erro apaga
      DELETE FROM proriedade_software
      WHERE id_propriedade_software = prop_software.id_propriedade_software;

    END LOOP;

    RAISE NOTICE 'Propriedade NULA no id %. Removendo...', cp.id_class_property;

    DELETE FROM computador_coleta_historico
    WHERE id_class_property = cp.id_class_property;

    DELETE FROM computador_coleta
    WHERE id_class_property = cp.id_class_property;

    RAISE NOTICE 'Processamento finalizado. Excluindo id_class_property = %', cp.id_class_property;

    -- Finalmente apaga a propriedade
    DELETE FROM class_property
    WHERE id_class_property = cp.id_class_property;

  END LOOP;

  -- Exclui ocorrências repetidas que possam existir para a classe SoftwareList
  SELECT min(id_class)
  INTO v_id_class
  FROM classe
  WHERE nm_class_name = 'SoftwareList';

  FOR cl IN select id_class
            from classe
            where nm_class_name = 'SoftwareList'
                  and id_class <> v_id_class
  LOOP

    RAISE NOTICE 'Excluindo classe id = %', cl.id_class;

    DELETE FROM class_property
    WHERE id_class = cl.id_class;

    DELETE FROM classe
    WHERE id_class = cl.id_class;

  END LOOP;

  RETURN;
END;
$$ LANGUAGE 'plpgsql';