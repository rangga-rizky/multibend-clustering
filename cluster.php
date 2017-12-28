<?php 
/**
* 
*/
class Cluster
{
    private $cluster;

    public function __construct($cluster){ 
        $this->cluster = $cluster; 
    }

    public function singleLinkage($min)
    {
        while (sizeof($this->cluster)>$min) {
           // echo sizeof($this->cluster).'<br>';
            $new_cluster=array(array());
            $distance=9999999;
            $closest_cluster=0;
            //searching the shortest distance from a cluster to another cluster 
            foreach ($this->cluster[0] as $data1) {

                for ($j=1; $j < sizeof($this->cluster) ; $j++) { 
                    foreach ($this->cluster[$j] as $data2) {
                        $new_distance=0;
                        for ($k=0; $k < 6; $k++) { 
                            $new_distance+=pow($data1[$k]-$data2[$k],2);
                        }
                        $new_distance=sqrt($new_distance);
                        if($new_distance<$distance){
                            $distance=$new_distance;
                            $closest_cluster=$j;
                        }
                    }       
                }
            }
            //merging a cluster to the closest cluster
            $cnt=0;
            for ($i=1; $i < sizeof($this->cluster); $i++) { 
                if($i!=$closest_cluster){
                    $new_cluster[$cnt]=$this->cluster[$i];
                    $cnt++;
                }
            }
            
            array_push($new_cluster,array_merge($this->cluster[0],$this->cluster[$closest_cluster]));
         
            unset($this->cluster);
            $this->cluster=$new_cluster;
            unset($new_cluster);
            unset($clustered_index);
        }
    }
    public function getCluster(){
        return $this->cluster;
    }
}
?>