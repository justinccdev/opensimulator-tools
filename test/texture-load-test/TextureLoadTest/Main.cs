using System;
using System.Collections.Generic;
using System.IO;
using System.Net;

namespace TextureLoadTest
{
    class MainClass
    {
        public static void Main(string[] args)
        {
            string getTextureUrl = args[0];
            string texturesPath = args[1];

            List<string> textureIds = new List<string>();

            using (StreamReader sr = new StreamReader(texturesPath))
            {
                string line;

                while ((line = sr.ReadLine()) != null)
                    textureIds.Add(line);
            }

            TextureLoadTest test = new TextureLoadTest(getTextureUrl, textureIds);
            test.Execute();
        }
    }
}