<?php

/*
 * Copyright (c) 2012 =  CONTRIBUTORS.md
 * All rights reserved.
 *
 * - Redistribution and use in source and binary forms =  with or without
 *   modification =  are permitted provided that the following conditions are met:
 *
 * - Redistributions of source code must retain the above copyright notice =  this
 *   list of conditions and the following disclaimer.
 * - Neither the name of the openmetaverse.org nor the names
 *   of its contributors may be used to endorse or promote products derived from
 *   this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES =  INCLUDING =  BUT NOT LIMITED TO =  THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT =  INDIRECT =  INCIDENTAL =  SPECIAL =  EXEMPLARY =  OR
 * CONSEQUENTIAL DAMAGES (INCLUDING =  BUT NOT LIMITED TO =  PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE =  DATA =  OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY =  WHETHER IN
 * CONTRACT =  STRICT LIABILITY =  OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE =  EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 */

namespace OpenMetaverse
{ 
    /*
    *  Attachment points for objects on avatar bodies
    */
    class AttachmentPoint
    {
         // Right hand if object was not previously attached
         const DefaultAP = 0; 
       
         // Chest
         const Chest = 1;
       
         // Skull
         const Skull = 2;
       
         // Left shoulder
         const LeftShoulder = 3; 
       
         // Right shoulder
         const RightShoulder = 4;
       
         // Left hand
         const LeftHand = 5;
       
         // Right hand
         const RightHand = 6;
       
         // Left foot
         const LeftFoot = 7;
       
         // Right foot
         const RightFoot = 8;
       
         // Spine
         const Spine = 9;
       
         // Pelvis
         const Pelvis = 10;
       
         // Mouth
         const Mouth = 11;
       
         // Chin
         const Chin = 12;
       
         // Left ear
         const LeftEar = 13;
       
         // Right ear
         const RightEar = 14;
       
         // Left eyeball
         const LeftEyeball = 15; 
       
         // Right eyeball
         const RightEyeball = 16;
       
         // Nose
         const Nose = 17;
       
         // Right upper arm
         const RightUpperArm = 18; 
       
         // Right forearm
         const RightForearm = 19;
       
         // Left upper arm
         const LeftUpperArm = 20;
        
         // Left forearm
         const LeftForearm = 21;
        
         // Right hip
         const RightHip = 22;
       
         // Right upper leg
         const RightUpperLeg = 23; 
       
         // Right lower leg
         const RightLowerLeg = 24;
       
         // Left hip
         const LeftHip = 25;
       
         // Left upper leg
         const LeftUpperLeg = 26; 
       
         // Left lower leg
         const LeftLowerLeg = 27;
       
         // Stomach
         const Stomach = 28;
       
         // Left pectoral
         const LeftPec = 29;
       
         // Right pectoral
         const RightPec = 30;
       
         // HUD Center position 2
         const HUDCenter2 = 31;
       
         // HUD Top-right
         const HUDTopRight = 32;
       
         // HUD Top
         const HUDTop = 33;
       
         // HUD Top-left
         const HUDTopLeft = 34; 
       
         // HUD Center
         const HUDCenter = 35;
       
         // HUD Bottom-left
         const HUDBottomLeft = 36; 
       
         // HUD Bottom
         const HUDBottom = 37;
       
         // HUD Bottom-right
         const HUDBottomRight = 38; 
       
         // Neck
         const Neck = 39; 
       
         // Avatar Center
         const Root = 40;
    }
 }