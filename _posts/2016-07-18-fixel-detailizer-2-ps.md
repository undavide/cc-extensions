---
title: 'Fixel Detailizer 2.5 PS'
date: 	2016-07-18
author: Fixel Algorithms
layout: post
class:  news
---
![Fixel Detailizer 2][1]

# Fixel Detailizer 2.5 PS

We released [Fixel Detailizer 2.5 PS][2] and it is a great opportunity to tell its story.

## Why Was It Created?
Well, our team is based on people who like Photography and happen to be engineers.  
We didn't like the fact Photoshop's Sharpening Tools are based on single Radius Parameters.  
It means you you can tweak the parameters to fit only some parts of the image, more accurately only one scale of details.  
We wanted Multi Scale Sharpening / Detail Enhancement which can fit all scales of the image at once.  
Coming form the Signal and Image Processing World we knew an approach called [Multi Scale Analysis][3].  
This is the sharpening and detail enhancement process we wanted, something to handle all scales of details in one tool.  
So we created it - [Fixel Detailizer - Multi Scale Detail Enhancer][2].

## Multi Scale Analysis
When we say Multi Scale Analysis, what do we mean?  
In a layman words, it means looking on the image at different resolutions / scales.  
For instance, given an image at a given size, the Multi Scale approach suggests processing it at its original size and half of that and half of that and so on...  
Well, at least it is one way to look at it.  

![Fixel Detailizer 2 Multi Scale][4]  

The approaches to implement Multi Scale Analysis are endless.  
Yet in the Image Processing World it all started with the [Image Pyramid][5].  
Later came the Filter Banks and the the [Wavelet Transform][6].  
Today there are many approaches based on different Blurring Operators (Yes, sharpening starts with blurring, even in Photoshop, but that's a story for another day).    
We, considering both speed and quality, chose the [Undecimated Wavelet Transform][6] to implement [Fixel Detailizer][2].    
Our guideline was quality and the [Undecimated Wavelet Transform][6] yields less artifacts ([Halos / Ringing][7]) than the classic Wavelets.    

We'll write more thoroughly on Multi Scale Image Analysis in one of our next posts.

## What's New on Fixel Detailizer 2.5 PS
Well, we added and improved numerous things:

 * New Beautiful UI  
Faster and more responsive UI with History Panel built in.
 * Added Luminosiy Mode  
Allows choosing whether to enhance details on Luminosity Channel (No effect of the Color Hue or Saturation) or all RGB channels.
 * Large Image Support  
Supporting images larger than 30,000 x 30,0000 rectangle.
 * Improved Performance  
Improved and more efficient algorithm which is ~5-10% faster.

Some lesser known features of Fixel Detailizer are:

 * 5 Bands / Scales to Set Details Amplification and Enhancement Level  
The user can set the amplification level of each detail scale as easy as setting amplification level for different bands in Hi-Fi Equalizer.
 * Preserve Saturation Mode  
The filter will affect the Luminosity channel only. No color shifting.
 * 32 Bit Internal Processing  
All the "Math" is done in 32 Bit precision to avoid clipping, quantization histogram distortions effects.
 * 8, 16 and 32 Bit Mode Support  
The filter supports 8, 16 and 32 Bit Mode (RGB & Grayscale) including support for HDR Photography
 * HDR Like Effect  
Setting high amplification for large scale details can achieve pseudo HDR effect.
 * Intuitive & User Friendly  
Full integration with Adobe Photoshop using Native Code API and Panel Extension UI.
 * Scriptable Filter  
The filter is fully scriptable (Adobe's ExtendScript aware) and can be integrated into advanced users automatic workflows (Script based workflows).

Some more information can be found at [Introducing Fixel Detailizer 2 PS][8] or [Fixel Detailizer 2.5 PS Quick Start Guide][9].


  [1]: {{site.baseurl}}/news/images/FixelDetailizer2Icon150px.png "Fixel Detailizer 2"
  [2]: http://fixelalgorithms.co/products/detailizer/
  [3]: https://en.wikipedia.org/wiki/Multiresolution_analysis
  [4]: {{site.baseurl}}/news/images/FixelDetailizerScales004.png "Fixel Detailizer 2 Multi Scale"
  [5]: https://en.wikipedia.org/wiki/Pyramid_(image_processing)
  [6]: https://en.wikipedia.org/wiki/Stationary_wavelet_transform
  [7]: https://en.wikipedia.org/wiki/Ringing_artifacts
  [8]: http://www.davidebarranca.com/2014/07/introducing-fixel-detailizer/
  [9]: http://fixelalgorithms.co/products/detailizer/pdf/Fixel%20Detailizer%202.5%20PS%20QuickStart.pdf