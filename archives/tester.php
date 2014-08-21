<?php


func algo1()
{
    println("ALGO1")
    
    var magicSquare = cMagicSquare()
    
    magicSquare.makeMagic(0, startCol: 2)

    magicSquare.dump()
    

}

func algo2(){
    println("ALGO2")

    var magicSquare = cMagicSquare()
    
    magicSquare.makeMagic2(0, startCol: 2)
    
    magicSquare.dump()

//    dumpMagicSquare(N,S)
}

func algo3(){
    println("ALGO3")
    var magicSquare = cMagicSquare()
    
    magicSquare.makeMagic3(0, startCol: 2)
    
    magicSquare.dump()
//    
}



?>
