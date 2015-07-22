<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * SoftwareRelatorioRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SoftwareRelatorioRepository extends EntityRepository
{
    public function gerarRelatorio($filtros) {

        $qb = $this->createQueryBuilder("rel")
            ->select(
                "so.teDescSo",
                "so.idSo",
                "so.inMswindows",
                "loc.idLocal",
                "loc.nmLocal",
                "r.teIpRede",
                "r.nmRede",
                "r.idRede",
                "rel.nomeRelatorio",
                "rel.idRelatorio",
                "COUNT(DISTINCT comp.idComputador) as numComp"
            )
            ->innerJoin("rel.softwares", "sw")
            ->innerJoin("CacicCommonBundle:PropriedadeSoftware", "prop", "WITH", "sw.idSoftware = prop.software")
            ->innerJoin("CacicCommonBundle:Computador", "comp", "WITH", "comp.idComputador = prop.computador")
            ->innerJoin("CacicCommonBundle:So", "so", "WITH", "so.idSo = comp.idSo")
            ->innerJoin("CacicCommonBundle:Rede", "r", "WITH", "comp.idRede = r.idRede")
            ->innerJoin("CacicCommonBundle:Local", "loc", "WITH", "loc.idLocal = r.idLocal")
            ->andWhere("comp.ativo IS NULL or comp.ativo = 't'")
            ->groupBy("so.teDescSo",
                "so.idSo",
                "so.inMswindows",
                "loc.idLocal",
                "loc.nmLocal",
                "r.teIpRede",
                "r.nmRede",
                "r.idRede",
                "rel.nomeRelatorio",
                "rel.idRelatorio"
            );

        if (!empty($filtros['softwares'])) {
            $softwares = $filtros['softwares'];
            $qb->andWhere("rel.idRelatorio IN ($softwares)");
        }

        if (!empty($filtros['nomeRelatorio'])) {
            $softwares = $filtros['nomeRelatorio'];
            $qb->andWhere("rel.nomeRelatorio IN ($softwares)");
        }

        if (!empty($filtros['locais'])) {
            $local_filter = $filtros['locais'];
            $qb->andWhere("loc.idLocal IN ($local_filter)");
        }

        if (!empty($filtros['redes'])) {
            $rede_filter = $filtros['redes'];
            $qb->andWhere("rel.idRelatorio IN ($rede_filter)");
        }

        if (!empty($filtros['so'])) {
            $so_filter = $filtros['so'];
            $qb->andWhere("rel.idRelatorio IN ($so_filter)");
        }

        return $qb->getQuery()->getArrayResult();
    }

    public function gerarRelatorioDetalhar($filtros) {

        $qb = $this->createQueryBuilder("rel")
            ->select(
                "so.teDescSo",
                "so.idSo",
                "so.inMswindows",
                "loc.idLocal",
                "loc.nmLocal",
                "r.teIpRede",
                "r.nmRede",
                "r.idRede",
                "rel.nomeRelatorio",
                "rel.idRelatorio",
                "comp.idComputador",
                "comp.nmComputador",
                "comp.teIpComputador",
                "comp.teNodeAddress",
                'comp.dtHrUltAcesso'
            )
            ->innerJoin("rel.softwares", "sw")
            ->innerJoin("CacicCommonBundle:PropriedadeSoftware", "prop", "WITH", "sw.idSoftware = prop.software")
            ->innerJoin("CacicCommonBundle:Computador", "comp", "WITH", "comp.idComputador = prop.computador")
            ->innerJoin("CacicCommonBundle:So", "so", "WITH", "so.idSo = comp.idSo")
            ->innerJoin("CacicCommonBundle:Rede", "r", "WITH", "comp.idRede = r.idRede")
            ->innerJoin("CacicCommonBundle:Local", "loc", "WITH", "loc.idLocal = r.idLocal")
            ->andWhere("comp.ativo IS NULL or comp.ativo = 't'")
            ->groupBy("so.teDescSo",
                "so.idSo",
                "so.inMswindows",
                "loc.idLocal",
                "loc.nmLocal",
                "r.teIpRede",
                "r.nmRede",
                "r.idRede",
                "rel.nomeRelatorio",
                "rel.idRelatorio",
                "comp.idComputador",
                "comp.nmComputador",
                "comp.teIpComputador",
                "comp.teNodeAddress",
                'comp.dtHrUltAcesso'
            );

        if (!empty($filtros['softwares'])) {
            $softwares = $filtros['softwares'];
            $qb->andWhere("rel.idRelatorio IN ($softwares)");
        }

        if (!empty($filtros['nomeRelatorio'])) {
            $softwares = $filtros['nomeRelatorio'];
            $software_filter = explode(", ", $softwares);
            $software_filter = implode("', '", $software_filter);
            $qb->andWhere("rel.nomeRelatorio IN ('$software_filter')");
        }

        if (!empty($filtros['locais'])) {
            $local_filter = $filtros['locais'];
            $qb->andWhere("loc.idLocal IN ($local_filter)");
        }

        if (!empty($filtros['redes'])) {
            $rede_filter = $filtros['redes'];
            $qb->andWhere("comp.idRede IN ($rede_filter)");
        }

        if (!empty($filtros['so'])) {
            $so_filter = $filtros['so'];
            $qb->andWhere("comp.idSo IN ($so_filter)");
        }

        return $qb->getQuery()->getArrayResult();
    }
}
