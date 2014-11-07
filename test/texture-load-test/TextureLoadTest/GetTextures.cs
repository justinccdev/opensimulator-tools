using System;
using System.Collections.Generic;
using System.IO;
using System.Net;
using System.Threading;

namespace TextureLoadTest
{
    public class GetTextures
    {
        private TextureLoadTest m_test;
        private string m_getTextureUrl;
        private List<string> m_textureIds;

        public GetTextures(TextureLoadTest test, string getTextureUrl, List<string> textureIds)
        {
            m_test = test;
            m_getTextureUrl = getTextureUrl;
            m_textureIds = textureIds;
        }

        public void Execute()
        {
            foreach (string textureId in m_textureIds)
            {
                string thisTextureUrl = string.Format("{0}?texture_id={1}", m_getTextureUrl, textureId);

                WebRequest req = HttpWebRequest.Create(thisTextureUrl);

                try
                {
                    using (WebResponse response = req.GetResponse())
                    {
//                        Console.WriteLine("Successfully executed {0} {1}", req.Method, req.RequestUri);

                        long len = response.ContentLength;
                        m_test.TotalBytes += len;

                        if (len <= 0)
                        {
                            Console.WriteLine("ERROR: Got length {0} for {1} {2}", len, req.Method, req.RequestUri);
                        }
                        else
                        {
                            Console.WriteLine("Got length {0} for {1} {2}", len, req.Method, req.RequestUri);
                            m_test.SuccessfulRequests++;
                        }
                    }
                }
                catch (WebException e)
                {
                    Console.WriteLine("ERROR: Got {0} on {1} {2}", e.Status, req.Method, req.RequestUri);
                }
            }
        }
    }
}