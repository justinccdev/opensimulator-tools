<?php
/*
 * Copyright (c) 2012, CONTRIBUTORS.md
 * All rights reserved.
 *
 * - Redistribution and use in source and binary forms, with or without
 *   modification, are permitted provided that the following conditions are met:
 *
 * - Redistributions of source code must retain the above copyright notice, this
 *   list of conditions and the following disclaimer.
 * - Neither the name of the openmetaverse.org nor the names
 *   of its contributors may be used to endorse or promote products derived from
 *   this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 */

namespace OpenMetaverse
{
    /*
     * Generic base class for enums.
     */
    abstract class Enum
    {              
        protected $valueToName;
        
        //static abstract function Init();
                
        /*
         * Resolve a value for this enum to a name.
         * 
         * @param int $value
         * 
         * @returns The name of the corresponding enum entry if found, otherwise NULL. 
         */
        protected static function GetNameStatic($singleton, $value)
        {
            if (array_key_exists($value, $singleton->valueToName))
                return $singleton->valueToName[$value];
            else
                return NULL;
        }        
    }
    
    /*
     * The different types of grid assets
     */
    class AssetType extends Enum
    {
        private static $singleton;
        
        // Unknown asset type
        const Unknown = -1;

        // Texture asset, stores in JPEG2000 J2C stream format
        const Texture = 0;

        // Sound asset
        const Sound = 1;

        // Calling card for another avatar
        const CallingCard = 2;

        // Link to a location in world
        const Landmark = 3;

        // Legacy script asset, you should never see one of these
        //[Obsolete]
        //const Script = 4;

        // Collection of textures and parameters that can be
        // worn by an avatar
        const Clothing = 5;

        // Primitive that can contain textures, sounds,
        // scripts and more
        const Object = 6;

        // Notecard asset
        const Notecard = 7;

        // Holds a collection of inventory items
        const Folder = 8;

        // Root inventory folder
        const RootFolder = 9;

        // Linden scripting language script
        const LSLText = 10;

        // LSO bytecode for a script
        const LSLBytecode = 11;

        // Uncompressed TGA texture
        const TextureTGA = 12;

        // Collection of textures and shape parameters that can
        // be worn
        const Bodypart = 13;

        // Trash folder
        const TrashFolder = 14;

        // Snapshot folder
        const SnapshotFolder = 15;

        // Lost and found folder
        const LostAndFoundFolder = 16;

        // Uncompressed sound
        const SoundWAV = 17;

        // Uncompressed TGA non-square image, not to be used as a
        // texture
        const ImageTGA = 18;

        // Compressed JPEG non-square image, not to be used as a
        // texture
        const ImageJPEG = 19;

        // Animation
        const Animation = 20;

        // Sequence of animations, sounds, chat, and pauses
        const Gesture = 21;

        // Simstate file
        const Simstate = 22;

        // Contains landmarks for favorites
        const FavoriteFolder = 23;

        // Asset is a link to another inventory item
        const Link = 24;

        // Asset is a link to another inventory folder
        const LinkFolder = 25;

        // Beginning of the range reserved for ensembles
        const EnsembleStart = 26;

        // End of the range reserved for ensembles
        const EnsembleEnd = 45;

        // Folder containing inventory links to wearables and attachments
        // that are part of the current outfit
        const CurrentOutfitFolder = 46;

        // Folder containing inventory items or links to
        // inventory items of wearables and attachments
        // together make a full outfit
        const OutfitFolder = 47;

        // Root folder for the folders of type OutfitFolder
        const MyOutfitsFolder = 48;

        // Linden mesh format
        const Mesh = 49;

        // Marketplace direct delivery inbox ("Received Items")
        const Inbox = 50;

        // Marketplace direct delivery outbox
        const Outbox = 51;

        static function Init()
        {
            self::$singleton = new AssetType();
            
            self::$singleton->valueToName = array(
                "-1" => "Unknown",
                 "0" => "Texture",
                 "1" => "Sound",
                 "2" => "CallingCard",
                 "3" => "Landmark",
                 "4" => "Script",
                 "5" => "Clothing",
                 "6" => "Object",
                 "7" => "Notecard",
                 "8" => "Folder",
                 "9" => "RootFolder",
                "10" => "LSLText",
                "11" => "LSLBytecode",
                "12" => "TextureTGA",
                "13" => "Bodypart",
                "14" => "TrashFolder",
                "15" => "SnapshotFolder",
                "16" => "LostAndFoundFolder",
                "17" => "SoundWAV",
                "18" => "ImageTGA",
                "19" => "ImageJPEG",
                "20" => "Animation",
                "21" => "Gesture",
                "22" => "Simstate",
                "23" => "FavoriteFolder",
                "24" => "Link",
                "25" => "LinkFolder",
                "26" => "EnsembleStart",
                "45" => "EnsembleEnd",
                "46" => "CurrentOutfitFolder",
                "47" => "OutfitFolder",
                "48" => "MyOutfitsFolder",
                "49" => "Mesh",
                "50" => "Inbox",
                "51" => "Outbox"
            );  
        } 

        public static function GetName($value)
        {
            return self::GetNameStatic(self::$singleton, $value);
        }     
    }

    //
    // Inventory Item Types, eg Script, Notecard, Folder, etc
    //
    class InventoryType extends Enum
    {
        private static $singleton;
        
        // Unknown
        const Unknown = -1;

        // Texture
        const Texture = 0;

        // Sound
        const Sound = 1;

        // Calling Card
        const CallingCard = 2;

        // Landmark
        const Landmark = 3;

        /*
         // Script
         //[Obsolete("See LSL")] Script = 4,
         // Clothing
         //[Obsolete("See Wearable")] Clothing = 5,
         // Object, both single and coalesced
         */

        const Object = 6;

        // Notecard
        const Notecard = 7;

        //
        const Category = 8;

        // Folder
        const Folder = 8;

        //
        const RootCategory = 9;

        // an LSL Script
        const LSL = 10;

        /*
         //
         //[Obsolete("See LSL")] LSLBytecode = 11,
         //
         //[Obsolete("See Texture")] TextureTGA = 12,
         //
         //[Obsolete] Bodypart = 13,
         //
         //[Obsolete] Trash = 14,
         */

        //
        const Snapshot = 15;

        /*
         //
         //[Obsolete] LostAndFound = 16,
         */

        //
        const Attachment = 17;

        //
        const Wearable = 18;

        //
        const Animation = 19;

        //
        const Gesture = 20;

        //
        const Mesh = 22;
        
        static function Init()
        {
            self::$singleton = new InventoryType();
            
            self::$singleton->valueToName = array(
                "-1" => "Unknown",
                 "0" => "Texture",
                 "1" => "Sound",
                 "2" => "CallingCard",
                 "3" => "Landmark",
                 "4" => "Script",
                 "5" => "Clothing",
                 "6" => "Object",
                 "7" => "Notecard",
                 "8" => "Folder",
                 "9" => "RootCategory",
                "10" => "LSL",
                "11" => "LSLBytecode",
                "12" => "TextureTGA",
                "13" => "Bodypart",
                "14" => "Trash",
                "15" => "Snapshot",
                "16" => "LostAndFound",
                "17" => "Attachment",
                "18" => "Wearable",
                "19" => "Animation",
                "20" => "Gesture",
                "22" => "Mesh",
            ); 
        }     
        
        public static function GetName($value)
        {
            return self::GetNameStatic(self::$singleton, $value);
        }                     
    }

    //
    // Item Sale Status
    //
    class SaleType extends Enum
    {
        private static $singleton;
        
        // Not for sale
        const Not = 0;

        // The original is for sale
        const Original = 1;

        // Copies are for sale
        const Copy = 2;

        // The contents of the object are for sale
        const Contents = 3;
        
        static function Init()
        {
            self::$singleton = new SaleType();
            
            self::$singleton->valueToName = array(
                 "0" => "Not",
                 "1" => "Original",
                 "2" => "Copy",
                 "3" => "Contents"
            );
        }    
        
        public static function GetName($value)
        {
            return self::GetNameStatic(self::$singleton, $value);
        }         
    }

    //
    // Types of wearable assets
    //
    class WearableType extends Enum
    {
        private static $singleton;
        
        // Body shape
        const Shape = 0;

        // Skin textures and attributes
        const Skin = 1;

        // Hair
        const Hair = 2;

        // Eyes
        const Eyes = 3;

        // Shirt
        const Shirt = 4;

        // Pants
        const Pants = 5;

        // Shoes
        const Shoes = 6;

        // Socks
        const Socks = 7;

        // Jacket
        const Jacket = 8;

        // Gloves
        const Gloves = 9;

        // Undershirt
        const Undershirt = 10;

        // Underpants
        const Underpants = 11;

        // Skirt
        const Skirt = 12;

        // Alpha mask to hide parts of the avatar
        const Alpha = 13;

        // Tattoo
        const Tattoo = 14;

        // Physics
        const Physics = 15;

        // Invalid wearable asset
        const Invalid = 255;
        
        static function Init()
        {
            self::$singleton = new WearableType();
            
            self::$singleton->valueToName = array(
                 "0" => "Shape",
                 "1" => "Skin",
                 "2" => "Hair",
                 "3" => "Eyes",
                 "4" => "Shirt",
                 "5" => "Pants",
                 "6" => "Shoes",
                 "7" => "Socks",
                 "8" => "Jacket",
                 "9" => "Gloves",
                "10" => "Undershirt",
                "11" => "Underpants",
                "12" => "Skirt",
                "13" => "Alpha",
                "14" => "Tattoo",
                "15" => "Physics",
                "16" => "Invalid"
            );   
        }    
        
        public static function GetName($value)
        {
            return self::GetNameStatic(self::$singleton, $value);
        }           
    }

    AssetType::Init();
    InventoryType::Init();
    SaleType::Init();
    WearableType::Init();
}

?>
