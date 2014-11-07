using System;
using System.Collections.Generic;
using System.IO;
using System.Net;
using System.Threading;

namespace TextureLoadTest
{
    public class TextureLoadTest
    {
        private string m_getTextureUrl;
        private List<string> m_textureIds;

        public int SuccessfulRequests { get; set; }
        public long TotalBytes { get; set; }

        public TextureLoadTest(string getTextureUrl, List<string> textureIds)
        {
            m_getTextureUrl = getTextureUrl;
            m_textureIds = textureIds;
        }

        public void Execute()
        {
            DateTime start = DateTime.Now;

            GetTextures gt = new GetTextures(this, m_getTextureUrl, m_textureIds);
            gt.Execute();

            TimeSpan elapsed = DateTime.Now - start;

            Console.WriteLine(
                "Successfully got {0} textures out of {1} requests totalling {2} bytes from capability {3} took {4} seconds (avg {5} seconds/texture, {6} kB/s)",
                SuccessfulRequests,
                m_textureIds.Count,
                TotalBytes,
                m_getTextureUrl,
                elapsed.TotalSeconds,
                elapsed.TotalSeconds / (double)m_textureIds.Count,
                TotalBytes / elapsed.TotalSeconds / 1024);
        }
    }
}