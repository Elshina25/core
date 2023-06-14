<?
class GraphTools {
  public static function Custom_Circular_Diagram($ImageHandle, $arr, $background_color, $diameter, $centerX, $centerY, $antialiase=true)
  {
    if($antialiase)
    {
      $ImageHandle_Saved = $ImageHandle;
      $diameter_saved = $diameter;
      $diameter=$diameter*5;
      $centerX=$centerX*5;
      $centerY=$centerY*5;
      $ImageHandle = CreateImageHandle($diameter, $diameter, "FFFFFF", true);
      imagefill($ImageHandle, 0, 0, imagecolorallocate($ImageHandle, 255,255,255));
    }
    $arr2 = array();
    $diameterX = $diameter;
//    $diameterY = intval($diameter*0.6);
    $diameterY = $diameter;
    if(count($arr)>0)
    {
      $sum = 0;
      foreach($arr as $sector)
      {
        $sum += $sector["COUNTER"];
      }
      $degree1=0;
      $p=0.0;
      $i=0;
      foreach($arr as $sector)
      {
        $p += $sector["COUNTER"]/$sum*360.0;
        ++$i;
        if ($i==count($arr))
        {
          $degree2 = 360;
        }
        else
        {
          $degree2 = intval($p);
        }
        if($degree2 > $degree1)
        {
          $dec = ReColor($sector["COLOR"]);
          $arr2[] = array(
            "DEGREE_1"    => $degree1,
            "DEGREE_2"    => $degree2,
            "COLOR"        => $sector["COLOR"],
            "IMAGE_COLOR"    => ImageColorAllocate ($ImageHandle, $dec[0], $dec[1], $dec[2]),
            "IMAGE_DARK"    => ImageColorAllocate ($ImageHandle, $dec[0]/1.5, $dec[1]/1.5, $dec[2]/1.5),
          );
          $degree1 = $degree2;
        }
      }
      if(count($arr2)>0)
      {
        $h = 15;
        if($antialiase)
        {
          $h = $h * 5;
        }
        for($i = 0; $i <= $h; $i++)
        {
          foreach($arr2 as $sector)
          {
            $degree1 = $sector["DEGREE_1"];
            $degree2 = $sector["DEGREE_2"];
            $difference = $degree2 - $degree1;
            $degree1 -= 180;
            $degree1 = $degree1<0?360+$degree1:$degree1;
            $degree2 -= 180;
            $degree2 = $degree2<0?360+$degree2:$degree2;
            $color = $i==$h?$sector["IMAGE_COLOR"]:$sector["IMAGE_DARK"];
            if ($difference==360)
              imageellipse($ImageHandle, $centerX, $centerY-$i, $diameterX, $diameterY, $color);
            else
              imagearc($ImageHandle, $centerX, $centerY-$i, $diameterX, $diameterY, $degree1, $degree2, $color);
          }
        }
        $i--;
        foreach($arr2 as $sector)
        {
          $degree1 = $sector["DEGREE_1"];
          $degree2 = $sector["DEGREE_2"];
          $difference = $degree2 - $degree1;
          $degree1 -= 180;
          $degree1 = $degree1<0?360+$degree1:$degree1;
          $degree2 -= 180;
          $degree2 = $degree2<0?360+$degree2:$degree2;
          $color = $i==$h?$sector["IMAGE_COLOR"]:$sector["IMAGE_DARK"];
          if ($difference==360)
            imagefilledellipse($ImageHandle, $centerX, $centerY-$i, $diameterX, $diameterY, $color);
          else
          {
            imagefilledarc($ImageHandle, $centerX, $centerY-$i, $diameterX, $diameterY, $degree1, $degree2, $color, IMG_ARC_PIE);
          }
        }
      }
    }
    else
    {
      $dec = ReColor($background_color);
      $color= ImageColorAllocate ($ImageHandle, $dec[0], $dec[1], $dec[2]);
      imagefilledellipse($ImageHandle, $centerX, $centerY, $diameterX, $diameterY, $color);
    }
    if($antialiase)
    {
      /** @noinspection PhpUndefinedVariableInspection */
      imagecopyresampled($ImageHandle_Saved, $ImageHandle, 0, 0, 0, 0, $diameter_saved, $diameter_saved, $diameter, $diameter);
    }
  }
}
