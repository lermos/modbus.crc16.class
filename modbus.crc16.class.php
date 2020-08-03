<?php
namespace Lermos\Builder\ModbusCRC16;

/*
 * The Class calculates Modbus CRC16 Checksum of input ASCII string
 * and returns string in HEX format
 */
class ModbusCRC16 {

   public static function getCRC(string $msg)
   {
     /**
     * If your input string is HEX-formatted you should comment #15 line
     * for example: $msg="0123" equals to 0x0123 without leading 0x
     */

     //conversion input string to plain HEX string. See comment above
     $msg =bin2hex($msg);
     //pack HEX-formatted $msg to the pure HEX array
     $data = pack('H*',$msg);
     //initialize crc as start HEX value;
     $crc = 0xFFFF;
     //loop $data array of HEX bytes
     for ($i = 0; $i < strlen($data); $i++)
     {
       //xor bits in the first byte of HEX value
       $crc ^=ord($data[$i]);
       //loop to shift every of 8 bits
       for ($j = 8; $j !=0; $j--)
       {
         //shift bits to the right and xor with polynome
         if (($crc & 0x0001) !=0)
         {
           $crc >>= 1;
           $crc ^= 0xA001;
         }
         else $crc >>= 1;
       }
     }
     //return the result as HEX-formatted string
     return sprintf('%04X', $crc);
   }

}

function clientSide(ModbusCRC16 $crc, $msg)
{
  $query=$crc->getCRC($msg);
  /*
   * if you need  HEX representation as encoded string
   * you can return the result as echo bin2hex($query);
   */
  echo $query;
}

/**
* testing MODBUS CRC for string "lermos" is CFA3 or 0xCFA3 or 43464133
*/
echo "MODBUS CRC16 of string 'lermos':\n";
clientSide(new ModbusCRC16, "lermos");

?>
